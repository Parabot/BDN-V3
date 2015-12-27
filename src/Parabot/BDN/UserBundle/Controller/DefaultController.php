<?php

namespace Parabot\BDN\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('UserBundle:Default:index.html.twig', array('name' => $name));
    }

    public function csrfAction()
    {
        $csrfToken = $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue();
        return new JsonResponse(array($csrfToken));
    }
}
