<?php

namespace Parabot\BDN\BotBundle\Controller;

use Aws\S3\S3Client;
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
     * @Route("/download/{type}")
     * @Template()
     *
     * @param Request $request
     * @param string  $type
     *
     * @return JsonResponse
     */
    public function downloadAction(Request $request, $type) {
        /**
         * @var TypeRepository $repository
         * @var TypeHelper     $typeHelper
         */
        $manager    = $this->getDoctrine()->getManager();
        $branch     = $request->query->get('branch');
        $typeHelper = $this->get('bot.type_helper');
        $repository = null;
        $download   = null;

        if($typeHelper->typeExists($type)) {
            $repository = $manager->getRepository($typeHelper->getRepositorySlug($type));

            $download = $repository->findLatestByStability(
                ! ($request->query->get('nightly') === 'true'),
                $branch
            );
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
            return new JsonResponse([ 'result' => 'No version of type or branch found' ], 404);
        }

        if($request->query->get('create') == 'true') {
            $client = new Client();
            $client->setVersion("2.5.1-RC-15816222");
            $client->setStable(false);
            $client->setReleaseDate(new \DateTime());

            $manager->persist($client);
            $manager->flush();
        }

        if($request->query->get('download') === 'true') {

            /** @var S3Client $aws */
            $aws = $this->get('aws.s3');

            $result = $aws->getObject([ 'Bucket' => 'parabot', 'Key' => 'artifacts/Parabot-V2.5.1.jar' ]);
            $body   = $result->get('Body');
            $body->rewind();
            file_put_contents($c->getPath() . 'client-2.jar', $body->read($result[ 'ContentLength' ]));
        }
    }

    /**
     * @Route("/create/{type}")
     * @Template()
     *
     * @param Request $request
     * @param Type    $type
     *
     * @return JsonResponse
     */
    public function createAction(Request $request, $type) {
        /**
         * @var TravisHelper $helper
         * @var TypeHelper $typeHelper
         */
        $helper = $this->get('bot.travis_helper');
        $typeHelper = $this->get('bot.type_helper');
        $r = $request->query->get('builder_id');

        if($typeHelper->typeExists($type)) {
            return new JsonResponse($helper->getLatestBuild($type->getTravisRepository(), $r));
        }
        return new JsonResponse(['result' => 'Unknown type requested'], 404);
    }
}
