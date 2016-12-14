<?php

namespace Parabot\BDN\BotBundle\Controller;

use Buzz\Message\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class SignatureController extends Controller {
    /**
     * @Route("list", name="signature_list")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request) {

        return new JsonResponse();
    }
}
