<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class InternalController extends Controller {

    /**
     * @Route("/internal/route/oauth/v2/token/{type}", name="internal_request_token_route", defaults={"type" = "frontend"})
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @param string  $type
     *
     * @return JsonResponse
     */
    public function routeTokenRequest(Request $request, $type) {
        $clientId     = $request->get('client_id');
        $grantType    = $request->get('grant_type');
        $code         = $request->get('code');
        $refreshToken = $request->get('refresh_token');
        $redirectUri  = $request->get('redirect_uri');

        $clientSecret = $this->getParameter($type . '_' . 'internal_oauth_secret');

        $curl = curl_init(
            $request->getScheme() . ':' . $this->generateUrl(
                'fos_oauth_server_token',
                [],
                UrlGeneratorInterface::NETWORK_PATH
            )
        );
        curl_setopt($curl, CURLOPT_POST, true);

        $args = [
            'client_id'     => $clientId,
            'redirect_uri'  => $redirectUri,
            'client_secret' => $clientSecret,
            'grant_type'    => $grantType,
        ];

        if($code != null) {
            $args[ 'code' ] = $code;
        }
        if($refreshToken != null) {
            $args[ 'refresh_token' ] = $refreshToken;
        }

        curl_setopt(
            $curl,
            CURLOPT_POSTFIELDS,
            $args
        );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $auth = curl_exec($curl);

        return new JsonResponse(json_decode($auth, true));
    }

}