<?php

namespace Parabot\BDN\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller {
    public function indexAction($name) {
        return $this->render('BDNUserBundle:Default:index.html.twig', [ 'name' => $name ]);
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
}
