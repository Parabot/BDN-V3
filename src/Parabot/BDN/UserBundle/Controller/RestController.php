<?php
/**
 * @author JKetelaar
 */
namespace Parabot\BDN\UserBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Parabot\BDN\UserBundle\Entity\OAuth\Client;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RestController extends FOSRestController{

    /**
     * @Route("/login/oauth/v2/token", name="create_token")
     */
    public function createTokenAction(Request $request){
        if ($this->getUser() == null){
            return $this->redirect("/api/users/login");
        }

        $clientManager = $this->get('fos_oauth_server.client_manager.default');

        /**
         * @var $client Client
         */
        $client = $clientManager->createClient();
        $client->setRedirectUris(array('http://v3.bdn.parabot.org'));
        $client->setAllowedGrantTypes(array('token', 'authorization_code'));
        $clientManager->updateClient($client);

        return $this->redirect($this->generateUrl('fos_oauth_server_authorize', array(
            'client_id'     => $client->getPublicId(),
            'redirect_uri'  => 'http://v3.bdn.parabot.org',
            'response_type' => 'code'
        )));
    }
}