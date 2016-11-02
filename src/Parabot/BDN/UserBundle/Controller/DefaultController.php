<?php

namespace Parabot\BDN\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Parabot\BDN\UserBundle\Service\LoginRequestManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller {

    /**
     * @Route("/unauthorised", name="unauthorised_notice")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function unAuthorisedAction(Request $request) {
        return new JsonResponse([ 'result' => 'User not authorized to access this page' ], 401);
    }

    /**
     * @Route("/csrf", name="get_csrf")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function csrfAction(Request $request) {
        $csrfToken = $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue();

        return new JsonResponse([ $csrfToken ]);
    }

    /**
     * @Route("/create/login", name="create_login")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createLoginAction(Request $request) {
        $context = $this->container->get('router')->getContext();

        $key = $this->get('login_request_manager')->insertRequest();
        $url = $this->get('router')->generate('open_login', [ 'key' => $key ]);

        $url = $context->getScheme() . '://' . $context->getHost() . ($context->getHttpPort(
            ) !== 80 ? ':' . $context->getHttpPort() : '') . $url;

        if($key === false) {
            return new JsonResponse([ 'error' => 'Couldn\'t generate a new key' ], 500);
        }

        return new JsonResponse([ 'url' => $url, 'key' => $key ]);
    }

    /**
     * @Route("/open/login/{key}", name="open_login")
     *
     * @param Request $request
     * @param string  $key
     *
     * @return RedirectResponse
     */
    public function openLoginAction(Request $request, $key) {
        $response = $this->redirect(
            $this->generateUrl('hwi_oauth_service_redirect', [ 'service' => 'forums' ])
        );

        $cookie = new Cookie(LoginRequestManager::KEY_COOKIE, $key, time() + 5 * 60);
        $response->headers->setCookie($cookie);

        return $response;
    }

    /**
     * Returns the API key for the user, once he has signed in correctly
     *
     * @Route("/retrieve/login/{key}", name="gather_login_key")
     *
     * @param Request $request
     * @param string  $key
     *
     * @return JsonResponse
     */
    public function retrieveApiLoginAction(Request $request, $key) {
        $api = $this->get('login_request_manager')->retrieveUserApiFromKey($key);
        if($api === false) {
            return new JsonResponse([ 'error' => 'No user found for this key' ], 404);
        } else {
            return new JsonResponse([ 'api' => $api ]);
        }
    }

    /**
     * @ApiDoc(
     *  description="Sends an invite to the logged in user",
     *  requirements={
     *  },
     *  parameters={
     *  }
     * )
     *
     * @Route("/slack", name="slack_invite")
     * @Method({"GET"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @PreAuthorize("isNotBanned()")
     */
    public function inviteToSlackAction(Request $request) {
        $result = $this->get('slack_manager')->inviteToChannel($this->getUser());

        return new JsonResponse($result, $result[ 'code' ]);
    }
}
