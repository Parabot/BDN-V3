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
     * @Route("/create_copy", name="create_copy_oauth")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createCopyAuthAction(Request $request) {
        if($this->get('request_access_evaluator')->isNotBanned() !== true) {
            $url = $this->generateUrl(
                'forums_users_login',
                [
                    'after_login_redirect' => $request->getSchemeAndHttpHost() . $this->generateUrl(
                        'create_copy_oauth',
                        [ 'clientId' => $request->get('clientId') ]
                    ),
                ]
            );
        } else {
            $url = $this->generateUrl(
                'fos_oauth_server_authorize',
                [
                    'client_id'     => $request->get('clientId'),
                    'redirect_uri'  => $request->getSchemeAndHttpHost() . $this->generateUrl('copy_oauth'),
                    'response_type' => 'code',
                ]
            );
        }

        return $this->redirect($url);
    }

    /**
     * @Route("/copy", name="copy_oauth")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function displayCopyAction(Request $request) {
        return $this->render('@BDNOAuthServer/Default/copy.html.twig', array(
            'key' => $request->get('code'),
        ));
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
