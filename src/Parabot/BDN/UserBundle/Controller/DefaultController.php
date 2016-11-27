<?php

namespace Parabot\BDN\UserBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller {

    /**
     * @Route("/is/loggedin", name="is_logged_in")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function isLoggedInAction(Request $request) {
        return new JsonResponse([ 'result' => ($this->get('request_access_evaluator')->isNotBanned()) === true ]);
    }

    /**
     * @Route("/loggedin", name="logged_in")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function loggedInAction(Request $request) {
        if($this->getUser() != null) {
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
            $response->headers->setCookie(
                new Cookie(
                    $this->getParameter('api_key_cookie'),
                    $this->getUser()->getApiKey(),
                    time() + (60 * 60 * 24 * 31),
                    '/',
                    $this->getParameter('valid_domain')
                )
            );
        } else {
            $response = new JsonResponse([ 'result' => 'Failed to login' ]);
        }

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
     * @Route("/log_in", name="forums_users_login")
     * @Method({"GET"})
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function loginRedirectAction(Request $request) {
        $redirect = $request->get($this->getParameter('redirect_url_cookie'));

        $clientRepository = $this->getDoctrine()->getRepository('BDNUserBundle:OAuth\Client');
        $response         = new RedirectResponse(
            $this->generateUrl('hwi_oauth_service_redirect', [ 'service' => 'forums' ])
        );

        if($redirect != null && $clientRepository->isValidRedirectUri($redirect)) {
            $cookie = new Cookie(
                $this->getParameter('redirect_url_cookie'),
                $redirect,
                time() + (60 * 5),
                '/',
                $this->getParameter('valid_domain')
            );
            $response->headers->setCookie($cookie);
        }

        return $response;
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
