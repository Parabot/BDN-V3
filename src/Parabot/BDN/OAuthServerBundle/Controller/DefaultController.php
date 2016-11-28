<?php

namespace Parabot\BDN\OAuthServerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller {

    /**
     * @Route("/create", name="create_oauth_application")
     *
     * @param Request $request
     *
     * @Method({"POST"})
     *
     * @return JsonResponse
     */
    public function createAuthAction(Request $request) {
        $clientCreator = $this->get('oauth_client_creator');
        $values        = [];

        foreach($clientCreator::ARGUMENTS as $item) {
            if(($value = $request->get($item)) != null) {
                $values[ $item ] = $value;
            }
        }

        return $clientCreator->createClient($values);
    }

    /**
     * @Route("/valid", name="valid_oauth")
     *
     * @param Request $request
     *
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function isValidOAuth(Request $request) {
        return new JsonResponse(
            [ 'result' => $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY') ]
        );
    }
}
