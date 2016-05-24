<?php

namespace Parabot\BDN\BotBundle\Controller;

use Parabot\BDN\BotBundle\Entity\Types\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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
        return array('name' => $type);
    }
}
