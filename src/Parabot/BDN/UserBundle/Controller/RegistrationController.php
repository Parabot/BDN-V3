<?php
/**
 * @author JKetelaar
 */
namespace Parabot\BDN\UserBundle\Controller;

use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RegistrationController extends BaseController {
    public function registerAction(Request $request) {
        return new JsonResponse('Access page through community', 404);
    }

}