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
     * @Route("/account/oauth/v2/token", name="create_token")
     */
    public function createTokenAction(Request $request){
        if ($this->getUser() == null){
            return $this->redirect("/api/users/login");
        }

        $clientManager = $this->get('fos_oauth_server.client_manager.default');
        $clientRepository = $this->getDoctrine()->getRepository('UserBundle:OAuth\\Client');

        $redirectUris = array('http://v3.bdn.parabot.org');

        /**
         * @var $clientInstance Client
         * @var $client Client
         */
        foreach($clientRepository->findAll() as $clientInstance){
            if (count(array_intersect($clientInstance->getRedirectUris(), $redirectUris)) > 0){
                return new JsonResponse(array('error' => 'Client already exists with one of your redirects', 400));
            }
        }

        $client = $clientManager->createClient();
        $client->setRedirectUris(array());
        $client->setAllowedGrantTypes(array('token', 'authorization_code'));
        $clientManager->updateClient($client);

        return new JsonResponse(
            array(
                'client_id' => $client->getPublicId(),
                'secret_id' => $client->getSecret(),
            )
        );
    }
}