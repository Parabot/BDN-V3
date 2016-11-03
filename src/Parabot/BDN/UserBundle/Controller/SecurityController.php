<?php
/**
 * @author JKetelaar
 */
namespace Parabot\BDN\UserBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends BaseController {

    /**
     * @Route("/login", name="bdn_login")
     */
    public function loginAction(Request $request) {
        $return = parent::loginAction($request);

        return new JsonResponse($return, 401);
    }

    protected function renderLogin(array $data) {
        return $data;
    }
}