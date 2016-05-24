<?php

namespace Parabot\BDN\BotBundle\Controller;

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
        var_dump($c->getPath());

        return [ 'name' => $type ];
    }
}
