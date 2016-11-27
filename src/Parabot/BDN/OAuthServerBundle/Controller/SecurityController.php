<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\OAuthServerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\SecurityContext;

class SecurityController extends Controller {
    /**
     * @Route("/auth_login", name="bdn_login_oauth_application")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function loginAction(Request $request) {
        $session = $request->getSession();

        if($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } elseif(null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        if($error) {
            $error = $error->getMessage();
        }

        $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);

        return $this->render(
            'JbxApiBundle:Security:login.html.twig',
            [
                'last_username' => $lastUsername,
                'error'         => $error,
            ]
        );
    }

    /**
     * @Route("/auth_login_check", name="bdn_login_check_oauth_application")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function loginCheckAction(Request $request) {
        new JsonResponse([ 'a' => 'b' ]);
    }
}