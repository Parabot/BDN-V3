<?php

namespace Parabot\BDN\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('UserBundle:Default:index.html.twig', array('name' => $name));
    }

    /**
     * @Route("/csrf", name="get_csrf")
     */
    public function csrfAction(Request $request)
    {
        $csrfToken = $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue();
        return new JsonResponse(array($csrfToken));
    }
}
