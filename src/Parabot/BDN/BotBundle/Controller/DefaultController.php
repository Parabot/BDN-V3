<?php

namespace Parabot\BDN\BotBundle\Controller;

use Parabot\BDN\BotBundle\BDNBotBundle;
use Parabot\BDN\BotBundle\Entity\Types\Client;
use Parabot\BDN\BotBundle\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Validator\Constraints\DateTime;

class DefaultController extends Controller
{
    /**
     * @Route("/download/{type}")
     * @Template()
     */
    public function indexAction($type)
    {
        $client = new Client();
        $client->setVersion("2.5.1-RC-15816222");
        $client->setStable(false);
        $client->setReleaseDate(new \DateTime());

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($client);
        $manager->flush();

        /**
         * @var ClientRepository $repository
         */
        $repository = $manager->getRepository('BDNBotBundle:Types\Client');
        var_dump($repository->findAllByStability(false));

        return array('name' => $type);
    }
}
