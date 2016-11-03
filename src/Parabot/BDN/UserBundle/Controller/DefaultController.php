<?php

namespace Parabot\BDN\UserBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Parabot\BDN\UserBundle\Service\LoginRequestManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller {

    /**
     * @Route("/loggedin", name="logged_in")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function loggedInAction(Request $request) {
        $redirect = $request->cookies->get($this->getParameter('redirect_url_cookie'));

        $response = new JsonResponse([ 'result' => 'You are now logged in' ]);

        if($redirect != null) {
            if( ! $this->get('url_utils')->isValidHostWithTLD($redirect)) {
                $redirect = null;
            }

            if($redirect != null) {
                $response = new RedirectResponse($redirect);
            }
        }

        $response->headers->clearCookie($this->getParameter('redirect_url_cookie'));

        return $response;
    }

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
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createLoginAction(Request $request) {
        $redirect = $request->request->get('redirect');
        $context  = $this->container->get('router')->getContext();

        $key = $this->get('login_request_manager')->insertRequest($redirect);
        $url = $this->get('router')->generate('open_login', [ 'key' => $key ]);

        $url = $context->getScheme() . '://' . $context->getHost() . ($context->getHttpPort(
            ) !== 80 ? ':' . $context->getHttpPort() : '') . $url;

        if($key === false) {
            $response = new JsonResponse([ 'error' => 'Could not generate a new key' ], 500);
        } else {
            $response = new JsonResponse([ 'url' => $url, 'key' => $key ]);
        }

        return $response;
    }

    /**
     * @Route("/open/login/{key}", name="open_login")
     *
     * @param Request $request
     * @param string  $key
     *
     * @return JsonResponse|RedirectResponse
     */
    public function openLoginAction(Request $request, $key) {
        $repository = $this->getDoctrine()->getRepository('BDNUserBundle:Login\RequestToken');
        $result     = $repository->findOneBy([ 'key' => $key ]);

        if($result != null) {

            $response = $this->redirect(
                $this->generateUrl('hwi_oauth_service_redirect', [ 'service' => 'forums' ])
            );

            $response->headers->setCookie(new Cookie(LoginRequestManager::KEY_COOKIE, $key, time() + 5 * 60));
            $response->headers->setCookie(
                new Cookie($this->getParameter('redirect_url_cookie'), $result->getRedirect(), time() + 5 * 60)
            );

            return $response;
        }

        return new JsonResponse(
            [
                'error'   => 'Unknown key given',
                'message' => 'If you came from the client, please contact an administrator',
            ]
        );
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
