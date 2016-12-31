<?php

namespace Parabot\BDN\UserBundle\Controller;

use AppBundle\Service\SerializerManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GroupController extends Controller {
    /**
     * @Route("/list", name="list_user_groups")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function isLoggedInAction(Request $request) {
        return new JsonResponse(
            [
                'groups' => SerializerManager::normalize(
                    $this->getDoctrine()->getRepository('BDNUserBundle:Group')->findAllNotBanned()
                ),
            ]
        );
    }
}
