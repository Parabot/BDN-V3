<?php
/**
 * @author JKetelaar
 */
namespace Parabot\BDN\UserBundle\Controller;

use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\HttpFoundation\Request;

class RegistrationController extends BaseController {
    public function registerAction(Request $request) {
        $response = parent::registerAction($request);

        return $response;
    }

}