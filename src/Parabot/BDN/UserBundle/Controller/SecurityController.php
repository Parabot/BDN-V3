<?php
/**
 * @author JKetelaar
 */
namespace Parabot\BDN\UserBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class SecurityController extends BaseController
{

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        $response = parent::loginAction($request);
        return $response;
//        return new JsonResponse($response);
    }

    protected function renderLogin(array $data)
    {
        return parent::renderLogin($data);
//        return $data;
    }
}