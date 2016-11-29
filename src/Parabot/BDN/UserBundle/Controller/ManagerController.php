<?php

namespace Parabot\BDN\UserBundle\Controller;

use AppBundle\Service\SerializerManager;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ManagerController extends Controller {
    /**
     * @Route("/get/{id}", name="get_user")
     *
     * @param Request $request
     * @param int     $id
     *
     * @return JsonResponse
     *
     * @PreAuthorize("isAdministrator()")
     */
    public function getAction(Request $request, $id) {
        $response       = new JsonResponse();
        $userRepository = $this->getDoctrine()->getRepository('BDNUserBundle:User');

        if(($user = $userRepository->findOneBy([ 'id' => $id ])) != null) {
            $response->setData(SerializerManager::normalize($user, 'json', [ 'default', 'administrators' ]));
        } else {
            $response->setData([ 'result' => 'Could not find user' ]);
            $response->setStatusCode(404);
        }

        return $response;
    }

    /**
     * @Route("/search/{by}/{value}", name="search_user")
     *
     * @param Request $request
     * @param string  $by
     * @param string  $value
     *
     * @return JsonResponse
     *
     * @PreAuthorize("isAdministrator()")
     */
    public function searchAction(Request $request, $by, $value) {
        $response       = new JsonResponse();
        $userRepository = $this->getDoctrine()->getRepository('BDNUserBundle:User');

        if(($user = $userRepository->findOneBy([ $by => $value ])) != null) {
            $response->setData(SerializerManager::normalize($user, 'json', [ 'default', 'administrators' ]));
        } else {
            $response->setData([ 'result' => 'Could not find user' ]);
            $response->setStatusCode(404);
        }

        return $response;
    }
}
