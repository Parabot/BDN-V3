<?php

namespace Parabot\BDN\BotBundle\Controller;

use AppBundle\Service\SerializerManager;
use Aws\S3\Exception\NoSuchKeyException;
use Aws\S3\S3Client;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Parabot\BDN\BotBundle\Entity\Types\Type;
use Parabot\BDN\BotBundle\Repository\TypeRepository;
use Parabot\BDN\BotBundle\Service\ParameterParser;
use Parabot\BDN\BotBundle\Service\TravisHelper;
use Parabot\BDN\BotBundle\Service\TypeHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Travis\Client\Entity\Build;

class BotController extends Controller
{

    const ALLOWED_BRANCHES = ['master', 'development'];

    /**
     * @Route("/download/provider", name="provider_download")
     *
     * @deprecated
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function downloadProviderAction(Request $request)
    {
        $server = $request->get('server');
        $nightly = $request->get('nightly');

        if ($nightly == null) {
            $nightly = 'false';
        }

        $type = $this->get('bot.type_helper')->serverToType($server);

        return $this->redirect(
            $this->generateUrl(
                'bot_download',
                [
                    'type' => $type,
                    'nightly' => $nightly,
                ]
            ),
            301
        );
    }

    /**
     * @ApiDoc(
     *  description="Returns the type for the given server",
     *  parameters={
     *      {"name"="server", "dataType"="string", "required"=true, "description"="Name of the server to get the type from"}
     *  }
     * )
     *
     * @Route("/server/type", name="server_type")
     * @Method({"GET"})
     *
     * Gets the server type from a server name
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getServerType(Request $request)
    {
        $server = $request->get('server');
        $type = $this->get('bot.type_helper')->serverToType($server);

        return new JsonResponse(['type' => $type, 'server' => $server]);
    }

    /**
     * @ApiDoc(
     *  description="Returns the requested download file",
     *  requirements={
     *      {
     *          "name"="type",
     *          "dataType"="string",
     *          "description"="type to be downloaded"
     *      }
     *  },
     *  parameters={
     *      {"name"="branch", "dataType"="string", "required"=false, "description"="Branch to be downloaded from"},
     *      {"name"="build", "dataType"="string", "required"=false, "description"="Travis build id to download"},
     *      {"name"="nightly", "dataType"="boolean", "required"=false, "description"="Defines if it should list nightly
     *      or stable types"}
     *  }
     * )
     *
     * @Route("/download/{type}", name="bot_download")
     * @Method({"GET"})
     *
     * @param Request $request
     * @param string $type
     *
     * @return JsonResponse
     */
    public function downloadAction(Request $request, $type)
    {
        /**
         * @var ObjectManager $manager
         * @var TypeRepository|EntityRepository $repository
         * @var TypeHelper $typeHelper
         */
        $manager = $this->getDoctrine()->getManager();
        $branch = $request->query->get('branch');
        $build = $request->query->get('build');
        $typeHelper = $this->get('bot.type_helper');
        $repository = null;
        $download = null;

        if ($typeHelper->typeExists($type)) {
            $repository = $manager->getRepository($typeHelper->getRepositorySlug($type));

            if ($build != null) {
                $download = $repository->findOneBy(['build' => $build]);
            } else {
                $download = $repository->findLatestByStability(
                    !(ParameterParser::parseStringToBoolean($request->query->get('nightly'))),
                    $branch
                );
            }
        } else {
            return new JsonResponse(['result' => 'Unknown type requested'], 404);
        }

        if ($download != null) {
            $result = $this->get('bot.download_manager')->provideDownload($download);
            if ($result === false) {
                return new JsonResponse(
                    [
                        'result' => 'Could not find requested type file',
                        'help' => 'Please ask an administrator to solve this issue',
                        'error_code' => '3H84STO8JYKB',
                    ], 500
                );
            } else {
                return $result;
            }
        } else {
            return new JsonResponse(['result' => 'No version of type, branch or build found'], 404);
        }
    }

    /**
     * @Route("/create/{type}")
     * @Method({"POST"})
     *
     * @param Request $request
     * @param string $type
     *
     * @return JsonResponse
     *
     * @TODO: Check if from PR, otherwise branch could still be master
     * @TODO: Finish Slack notifications, by adding error messages
     */
    public function createAction(Request $request, $type)
    {
        /**
         * @var TravisHelper $travisHelper
         * @var TypeHelper $typeHelper
         * @var S3Client $aws
         */
        $travisHelper = $this->get('bot.travis_helper');
        $typeHelper = $this->get('bot.type_helper');
        $aws = $this->get('aws.s3');

        $id = $request->request->get('build_id');
        $version = $request->request->get('version');

        if ($typeHelper->typeExists($type)) {
            $manager = $this->getDoctrine()->getManager();
            /** @var TypeRepository|EntityRepository $repository */
            $repository = $manager->getRepository($typeHelper->getRepositorySlug($type));
            $typeObject = $typeHelper->createType($type);

            if ($id === 'latest') {
                $travis = $travisHelper->getRepository($typeHelper->createType($type)->getTravisRepository());
                $branch = $request->query->get('branch');
                if ($branch != null) {
                    /**
                     * @var Build $build
                     */
                    foreach ($travis->getBuilds()->toArray() as $build) {
                        if ($build->getBranch() == $branch) {
                            $id = $build->getId();
                            break;
                        }
                    }
                } else {
                    $id = $travis->getLastBuildId();
                }
            }

            $build = $travisHelper->getLatestBuild($typeObject->getTravisRepository(), $id);

            if ($build != null) {
                if (!in_array(strtolower($build->getBranch()), self::ALLOWED_BRANCHES)) {
                    return new JsonResponse(['result' => 'Given branch is not allowed'], 500);
                }

                if ($build->getResult() !== $build::RESULT_FAILED) {
                    $totalNamedVersion = $typeObject->getName();
                    if ($build->getBranch() != 'master') {
                        $version .= '-RC-'.$build->getId();
                    }
                    $totalNamedVersion .= '-V'.$version;

                    if (($result = $repository->findOneBy(['version' => $version])) == null || sizeof(
                            $result
                        ) <= 0) {
                        $location = 'artifacts/'.strtolower(
                                $typeObject->getType()
                            ).'/'.$totalNamedVersion.'.jar';

                        try {
                            $result = $aws->getObject(
                                [
                                    'Bucket' => 'parabot',
                                    'Key' => $location,
                                ]
                            );
                        } catch (NoSuchKeyException $e) {
                            return new JsonResponse(
                                [
                                    'result' => 'File could not be found on external server',
                                    'error_code' => '8I8HQFV2PAZJ',
                                ], 500
                            );
                        } catch (\Exception $e) {
                            return new JsonResponse(
                                ['result' => 'Something went terribly wrong', 'error_code' => '9I8IQFV1PGZJ'], 500
                            );
                        }

                        $typeObject->setVersion($version);
                        $typeObject->setStable($build->getBranch() == 'master');
                        $typeObject->setReleaseDate($build->getFinishedAt());
                        $typeObject->setBranch($build->getBranch());
                        $typeObject->setBuild($build->getId());

                        $manager->persist($typeObject);
                        $manager->flush();

                        $body = $result->get('Body');
                        $body->rewind();

                        file_put_contents(
                            $typeObject->getPath().$version.'.jar',
                            $body->read($result['ContentLength'])
                        );

                        if (($silent = $request->get('silent')) == null || $silent != '1') {
                            $this->get('slack_manager')->sendSuccessMessage(
                                'New release available',
                                'Created new '.$typeObject->getType().' from latest '.($typeObject->getStable(
                                ) ? '' : 'nightly ').'build.',
                                $request->getSchemeAndHttpHost().$this->generateUrl(
                                    'bot_download',
                                    ['type' => strtolower($typeObject->getType()), 'build' => $typeObject->getBuild()]
                                ),
                                [
                                    'Stable' => ucfirst($typeObject->getStable() ? 'true' : 'false'),
                                    'Build' => ucfirst($typeObject->getBuild()),
                                    'Version' => ucfirst($typeObject->getVersion()),
                                    'Branch' => ucfirst($typeObject->getBranch()),
                                ],
                                $build->getBranch() == 'master' ? 'news' : null
                            );
                        }

                        return new JsonResponse(
                            ['result' => 'Created new '.$typeObject->getName().' from latest build']
                        );
                    } else {
                        return new JsonResponse(
                            ['result' => 'Version '.$version.' already exists'], 500
                        );
                    }
                } else {
                    return new JsonResponse(['result' => 'Cannot create a release of a failed build'], 400);
                }
            } else {
                return new JsonResponse(['result' => 'Unknown build requested'], 404);
            }
        }

        return new JsonResponse(['result' => 'Unknown type requested'], 404);
    }

    /**
     * @ApiDoc(
     *  description="Compares a version against the latest version",
     *  requirements={
     *      {
     *          "name"="type",
     *          "dataType"="string",
     *          "description"="type to be compared to"
     *      },
     *      {
     *          "name"="current",
     *          "dataType"="string",
     *          "description"="current version"
     *      }
     *  }
     * )
     *
     * @Route("/compare/{type}/{current}")
     * @Method({"GET"})
     *
     * @param Request $request
     * @param string $type
     *
     * @param string $current
     *
     * @return JsonResponse
     */
    public function compareVersionsAction(Request $request, $type, $current)
    {
        /**
         * @var TypeHelper $typeHelper
         */
        $typeHelper = $this->get('bot.type_helper');

        if ($typeHelper->typeExists($type)) {
            /**
             * @var TypeRepository|EntityRepository $repository
             * @var Type $currentObject
             * @var Type $latestObject
             */
            $repository = $this->getDoctrine()->getManager()->getRepository($typeHelper->getRepositorySlug($type));
            $currentResult = $repository->findBy(['version' => $current], ['releaseDate' => 'DESC'], 1);
            if ($currentResult != null && sizeof($currentResult) == 1) {
                $currentObject = $currentResult[0];
                $latestResult = $repository->findBy(
                    ['stable' => $currentObject->getStable()],
                    ['releaseDate' => 'DESC'],
                    1
                );
                if ($latestResult != null && sizeof($latestResult) == 1) {
                    $latestObject = $latestResult[0];
                    $latest = boolval(
                        $latestObject->getReleaseDate() <= $currentObject->getReleaseDate()
                    );

                    return new JsonResponse(
                        [
                            'result' => $latest,
                            'message' => 'There is '.($latest ? 'a' : 'no').' new release',
                        ]
                    );
                } else {
                    return new JsonResponse(
                        ['result' => 'Something went terribly wrong', 'error_code' => '8AOB1SH67A'], 500
                    );
                }
            } else {
                return new JsonResponse(
                    [
                        'result' => false,
                        'message' => 'There is a new release',
                    ]
                );
            }
        } else {
            return new JsonResponse(['result' => 'Unknown type requested'], 404);
        }
    }

    /**
     * @ApiDoc(
     *  description="Compares a checksum against the latest version",
     *  requirements={
     *      {
     *          "name"="type",
     *          "dataType"="string",
     *          "description"="type to be compared to"
     *      },
     *      {
     *          "name"="version",
     *          "dataType"="string",
     *          "description"="current version checksum"
     *      }
     *  }
     * )
     *
     * @Route("/checksum/{type}/{version}")
     * @Method({"POST"})
     *
     * @param Request $request
     * @param string $type
     * @param string $version
     *
     * @return JsonResponse
     *
     */
    public function compareChecksumAction(Request $request, $type, $version)
    {
        /**
         * @var TypeHelper $typeHelper
         * @var string $currentMD5
         */
        $typeHelper = $this->get('bot.type_helper');
        $currentMD5 = $request->request->get('checksum');

        if ($currentMD5 != null && strlen($currentMD5) == 32 && ctype_xdigit($currentMD5)) {
            if ($typeHelper->typeExists($type)) {
                /**
                 * @var TypeRepository|EntityRepository $repository
                 * @var Type $currentObject
                 * @var Type $latestObject
                 */
                $repository = $this->getDoctrine()->getManager()->getRepository(
                    $typeHelper->getRepositorySlug($type)
                );
                $currentObject = $repository->findOneBy(['version' => $version], ['releaseDate' => 'DESC']);
                if ($currentObject != null) {
                    $latestMD5 = md5(file_get_contents($currentObject->getFile()));

                    return new JsonResponse(
                        [
                            'result' => $match = boolval($latestMD5 == $currentMD5),
                            'message' => 'Given checksum does'.($match === 'true' ? '' : ' not').' match the checksum of the given version',
                        ]
                    );
                } else {
                    return new JsonResponse(['result' => 'Unknown version requested'], 404);
                }
            } else {
                return new JsonResponse(['result' => 'Unknown type requested'], 404);
            }
        } else {
            return new JsonResponse(['result' => 'No valid checksum given'], 404);
        }
    }

    /**
     * @ApiDoc(
     *  description="Lists a type",
     *  requirements={
     *      {
     *          "name"="type",
     *          "dataType"="string",
     *          "description"="type to be compared to"
     *      }
     *  },
     *  parameters={
     *      {"name"="nightly", "dataType"="boolean", "required"=false, "description"="Defines if it should list nightly
     *      or stable types"},
     *      {"name"="limit", "dataType"="integer", "required"=false, "description"="Sets the limit of the amount to be
     *      shown, max 30"},
     *      {"name"="page", "dataType"="integer", "required"=false, "description"="The page of the list, combined with
     *      the limit"},
     *      {"name"="latest", "dataType"="boolean", "required"=false, "description"="Returns the latest type, whereas
     *      it will return an object instead of an array"}
     *  }
     * )
     *
     * @Route("/list/{type}")
     * @Method({"GET"})
     *
     * @param Request $request
     * @param string $type
     *
     * @return JsonResponse
     */
    public function listBuildVersionsAction(Request $request, $type)
    {
        /**
         * @var TypeHelper $typeHelper
         * @var bool $nightly
         */
        $typeHelper = $this->get('bot.type_helper');
        $stable = !ParameterParser::parseStringToBoolean($request->query->get('nightly'));

        if (($limit = $request->query->get('limit')) != null) {
            $limit = intval($limit);
            if ($limit > 30 || $limit < 0) {
                $limit = 30;
            }
        } else {
            $limit = 30;
        }

        if (($page = $request->query->get('page')) != null) {
            $page = intval($page);
            if ($page < 0) {
                $page = null;
            } else {
                $page = $limit * $page;
            }
        } else {
            $page = null;
        }

        if (($latest = $request->query->get('latest')) != null) {
            if (ParameterParser::parseStringToBoolean($latest)) {
                $limit = 1;
                $page = 0;
            }
        }

        if ($typeHelper->typeExists($type)) {
            /** @var TypeRepository|EntityRepository $repository */
            $repository = $this->getDoctrine()->getManager()->getRepository($typeHelper->getRepositorySlug($type));

            /** @var Type[] $typeList */
            $typeList = $repository->findBy(
                ['active' => true, 'stable' => $stable],
                ['releaseDate' => 'DESC'],
                $limit,
                $page
            );
            $typeListJson = [];
            if ($typeList != null && sizeof($typeList) > 1) {
                foreach ($typeList as $t) {
                    $typeListJson[] = SerializerManager::normalize($t);

                }
            } elseif ($typeList != null && sizeof($typeList) > 0) {
                $typeListJson = SerializerManager::normalize($typeList[0]);
            }

            return new JsonResponse($typeListJson);
        } else {
            return new JsonResponse(['result' => 'Unknown type requested'], 404);
        }
    }
}
