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
     * @PreAuthorize("isNotBanned()")
     */
    public function getAction(Request $request, $id) {
        $response       = new JsonResponse();
        $userRepository = $this->getDoctrine()->getRepository('BDNUserBundle:User');

        $loggedUser = $this->get('request_access_evaluator')->getUser();
        if($loggedUser != null && $loggedUser->getId() == $id) {
            $response->setData(SerializerManager::normalize($loggedUser, 'json', [ 'default', 'owner' ]));
        } else {
            if(($user = $userRepository->findOneBy([ 'id' => $id ])) != null) {
                $response->setData(SerializerManager::normalize($user, 'json', [ 'default', 'administrators' ]));
            } else {
                $response->setData([ 'result' => 'Could not find user' ]);
                $response->setStatusCode(404);
            }
        }

        return $response;
    }

    /**
     * @Route("/list/{page}", name="list_users")
     *
     * @param Request $request
     * @param int     $page
     *
     * @return JsonResponse
     *
     * @PreAuthorize("isAdministrator()")
     */
    public function listAction(Request $request, $page = 1) {
        $response       = new JsonResponse();
        $userRepository = $this->getDoctrine()->getRepository('BDNUserBundle:User');
        $users          = $userRepository->getForPage($page);

        $pageResult  = SerializerManager::normalize($users, 'json', [ 'default', 'administrators' ]);
        $totalResult = $userRepository->countTotal();
        $response->setData([ 'result' => $pageResult, 'total' => $totalResult ]);

        return $response;
    }

    /**
     * @Route("/search/username/{value}", name="search_user")
     *
     * @param Request $request
     * @param string  $value
     *
     * @return JsonResponse
     *
     * @PreAuthorize("isAdministrator()")
     */
    public function searchAction(Request $request, $value) {
        $response       = new JsonResponse();
        $userRepository = $this->getDoctrine()->getRepository('BDNUserBundle:User');
        $page           = ($page = $request->get('page')) != null ? intval($page) : 1;
        $totalResults   = $userRepository->countSearchByUsername($value);

        if(($users = $userRepository->searchByUsername($value, $page)) != null) {
            $pageResults = SerializerManager::normalize($users, 'json', [ 'default', 'administrators' ]);
            $response->setData([ 'result' => $pageResults, 'total' => $totalResults ]);
        } else {
            $response->setData([ 'result' => 'Could not find users', 'total' => $totalResults ]);
            $response->setStatusCode(404);
        }

        return $response;
    }
}
