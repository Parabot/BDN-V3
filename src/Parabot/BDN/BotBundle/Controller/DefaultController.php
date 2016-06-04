<?php

namespace Parabot\BDN\BotBundle\Controller;

use Aws\S3\Exception\NoSuchKeyException;
use Aws\S3\S3Client;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Parabot\BDN\BotBundle\BDNBotBundle;
use Parabot\BDN\BotBundle\Entity\Types\Client;
use Parabot\BDN\BotBundle\Entity\Types\Type;
use Parabot\BDN\BotBundle\Repository\ClientRepository;
use Parabot\BDN\BotBundle\Repository\TypeRepository;
use Parabot\BDN\BotBundle\Service\TravisHelper;
use Parabot\BDN\BotBundle\Service\TypeHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;

class DefaultController extends Controller {

    /**
     * @Route("/download/{type}", name="bot_download")
     * @Template()
     *
     * @param Request $request
     * @param string  $type
     *
     * @return JsonResponse
     */
    public function downloadAction(Request $request, $type) {
        /**
         * @var ObjectManager                   $manager
         * @var TypeRepository|EntityRepository $repository
         * @var TypeHelper                      $typeHelper
         */
        $manager    = $this->getDoctrine()->getManager();
        $branch     = $request->query->get('branch');
        $build      = $request->query->get('build');
        $typeHelper = $this->get('bot.type_helper');
        $repository = null;
        $download   = null;

        if($typeHelper->typeExists($type)) {
            $repository = $manager->getRepository($typeHelper->getRepositorySlug($type));

            if($build != null) {
                $download = $repository->findOneBy([ 'build' => $build ]);
            } else {
                $download = $repository->findLatestByStability(
                    ! ($request->query->get('nightly') === 'true'),
                    $branch
                );
            }
        } else {
            return new JsonResponse([ 'result' => 'Unknown type requested' ], 404);
        }

        if($download != null) {
            $result = $this->get('bot.download_manager')->provideDownload($download);
            if($result === false) {
                return new JsonResponse(
                    [
                        'result'     => 'Could not find requested type file',
                        'help'       => 'Please ask an administrator to solve this issue',
                        'error_code' => '3H84STO8JYKB',
                    ], 500
                );
            } else {
                return $result;
            }
        } else {
            return new JsonResponse([ 'result' => 'No version of type, branch or build found' ], 404);
        }
    }

    /**
     * @Route("/create/{type}")
     * @Template()
     *
     * @param Request $request
     * @param string  $type
     *
     * @return JsonResponse
     *
     *
     * @TODO: Check if from PR, otherwise branch could still be master
     * @TODO: Finish Slack notifications, by adding error messages
     */
    public function createAction(Request $request, $type) {
        /**
         * @var TravisHelper $travisHelper
         * @var TypeHelper   $typeHelper
         * @var S3Client     $aws
         */
        $travisHelper = $this->get('bot.travis_helper');
        $typeHelper   = $this->get('bot.type_helper');
        $aws          = $this->get('aws.s3');

        $id      = $request->query->get('build_id');
        $version = $request->query->get('version');

        if($typeHelper->typeExists($type)) {
            $manager = $this->getDoctrine()->getManager();
            /** @var TypeRepository|EntityRepository $repository */
            $repository = $manager->getRepository($typeHelper->getRepositorySlug($type));
            $typeObject = $typeHelper->createType($type);
            $build      = $travisHelper->getLatestBuild($typeObject->getTravisRepository(), $id);

            if($build != null) {
                if($build->getResult() === $build::RESULT_OK) {
                    $totalNamedVersion = $typeObject->getName();
                    if($build->getBranch() != 'master') {
                        $version .= '-RC-' . $build->getId();
                    }
                    $totalNamedVersion .= '-V' . $version;

                    if(($result = $repository->findOneBy([ 'version' => $version ])) == null || sizeof(
                                                                                                    $result
                                                                                                ) <= 0
                    ) {
                        $location = 'artifacts/' . strtolower(
                                $typeObject->getType()
                            ) . '/' . $totalNamedVersion . '.jar';

                        try {
                            $result = $aws->getObject(
                                [
                                    'Bucket' => 'parabot',
                                    'Key'    => $location,
                                ]
                            );
                        } catch(NoSuchKeyException $e) {
                            return new JsonResponse(
                                [
                                    'result'     => 'File could not be found on external server',
                                    'error_code' => '8I8HQFV2PAZJ',
                                ], 500
                            );
                        } catch(\Exception $e) {
                            return new JsonResponse(
                                [ 'result' => 'Something went terribly wrong', 'error_code' => '9I8IQFV1PGZJ' ], 500
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
                            $typeObject->getPath() . $version . '.jar',
                            $body->read($result[ 'ContentLength' ])
                        );

                        $this->get('slack_manager')->sendSuccessMessage(
                            'New release available',
                            'Created new ' . $typeObject->getName() . ' from latest ' . ($typeObject->getStable(
                            ) ? '' : 'nightly ') . 'build.',
                            $request->getSchemeAndHttpHost() . $this->generateUrl(
                                'bot_download',
                                [ 'type' => strtolower($typeObject->getType()), 'build' => $typeObject->getBuild() ]
                            ),
                            [
                                'Stable'  => ucfirst($typeObject->getStable() ? 'true' : 'false'),
                                'Build'   => ucfirst($typeObject->getBuild()),
                                'Version' => ucfirst($typeObject->getVersion()),
                                'Branch'  => ucfirst($typeObject->getBranch()),
                            ],
                            $build->getBranch() == 'master' ? 'news' : null
                        );

                        return new JsonResponse(
                            [ 'result' => 'Created new ' . $typeObject->getName() . ' from latest build' ]
                        );
                    } else {
                        return new JsonResponse(
                            [ 'result' => 'Version ' . $version . ' already exists', 500 ]
                        );
                    }
                }
            } else {
                return new JsonResponse([ 'result' => 'Unknown build requested' ], 400);
            }
        }

        return new JsonResponse([ 'result' => 'Unknown type requested' ], 404);
    }

    /**
     * @Route("/list/{type}")
     * @Template()
     *
     * @param Request $request
     * @param string  $type
     *
     * @return JsonResponse
     */
    public function listBuildVersionsAction(Request $request, $type) {
        /**
         * @var TypeHelper $typeHelper
         * @var bool       $nightly
         */
        $typeHelper = $this->get('bot.type_helper');
        $stable     = $request->query->get('stable') == 'false' ? false : true;

        if(($limit = $request->query->get('limit')) != null) {
            $limit = intval($limit);
            if($limit > 30 || $limit < 0) {
                $limit = 30;
            }
        } else {
            $limit = 30;
        }

        if(($page = $request->query->get('page')) != null) {
            $page = intval($page);
            if($page < 0) {
                $page = null;
            } else {
                $page = $limit * $page;
            }
        } else {
            $page = null;
        }

        if(($latest = $request->query->get('latest')) != null) {
            if($latest == 'true') {
                $limit = 2;
                $page  = 0;
            }
        }

        if($typeHelper->typeExists($type)) {
            /** @var TypeRepository|EntityRepository $repository */
            $repository = $this->getDoctrine()->getManager()->getRepository($typeHelper->getRepositorySlug($type));

            /** @var Type[] $typeList */
            $typeList     = $repository->findBy(
                [ 'active' => true, 'stable' => $stable ],
                [ 'releaseDate' => 'DESC' ],
                $limit,
                $page
            );
            $typeListJson = [ ];

            foreach($typeList as $t) {
                $typeListJson[ $t->getId() ] = [
                    'build'   => $t->getBuild(),
                    'version' => $t->getVersion(),
                    'release' => $t->getReleaseDate()->format('d-m-Y H:i'),
                    'stable'  => $t->getStable(),
                ];
            }

            return new JsonResponse($typeListJson);
        } else {
            return new JsonResponse([ 'result' => 'Unknown type requested' ], 404);
        }
    }
}
