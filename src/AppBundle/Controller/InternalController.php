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
     * @Route("/internal/route/oauth/v2/token", name="internal_request_token_route")
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function routeTokenRequest(Request $request) {
        $clientId     = $request->get('client_id');
        $grantType    = $request->get('grant_type');
        $code         = $request->get('code');
        $redirectUri  = $request->get('redirect_uri');
        $clientSecret = $this->getParameter('internal_oauth_secret');

        $curl = curl_init(
            $request->getScheme() . ':' . $this->generateUrl(
                'fos_oauth_server_token',
                [],
                UrlGeneratorInterface::NETWORK_PATH
            )
        );
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt(
            $curl,
            CURLOPT_POSTFIELDS,
            [
                'client_id'     => $clientId,
                'redirect_uri'  => $redirectUri,
                'client_secret' => $clientSecret,
                'code'          => $code,
                'grant_type'    => $grantType,
            ]
        );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $auth = curl_exec($curl);

        return new JsonResponse(json_decode($auth, true));
    }

}