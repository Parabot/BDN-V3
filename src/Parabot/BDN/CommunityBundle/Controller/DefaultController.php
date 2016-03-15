<?php

namespace Parabot\BDN\CommunityBundle\Controller;

use Parabot\BDN\CommunityBundle\Service\Connector;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/hello/{name}")
     * @Template()
     */
    public function indexAction($name)
    {
        /**
         * @var $connector Connector
         */
        $connector = $this->get('community.connector');
        var_dump($connector->performLogin('asd', 'asd'));

        return array('name' => $name);
    }
}
