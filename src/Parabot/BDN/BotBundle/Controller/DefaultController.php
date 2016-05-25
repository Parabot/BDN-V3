<?php

namespace Parabot\BDN\BotBundle\Controller;

use Aws\Credentials\Credentials;
use Aws\S3\S3Client;
use Aws\Sdk;
use Parabot\BDN\BotBundle\BDNBotBundle;
use Parabot\BDN\BotBundle\Entity\Types\Client;
use Parabot\BDN\BotBundle\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
     * @return array
     */
    public function indexAction(Request $request, $type) {
        $manager = $this->getDoctrine()->getManager();

        if($request->query->get('create') == 'true') {
            $client = new Client();
            $client->setVersion("2.5.1-RC-15816222");
            $client->setStable(false);
            $client->setReleaseDate(new \DateTime());

            $manager->persist($client);
            $manager->flush();
        }

        /** @var ClientRepository $repository */
        $repository = $manager->getRepository('BDNBotBundle:Types\Client');

        $c = $repository->findLatestByStability($request->query->get('nightly') === 'false');
//        var_dump($c->getPath());

        /** @var S3Client $aws */
        $aws = $this->get('aws.s3');

        $result = $aws->getObject(['Bucket' => 'parabot', 'Key' => 'artifacts/Parabot-V2.5.1.jar']);
        $body = $result->get('Body');
        $body->rewind();
        file_put_contents($c->getPath() . 'client-2.jar', $body->read($result['ContentLength']));

        return [ 'name' => $type ];
    }
}
