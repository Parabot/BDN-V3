<?php

namespace AppBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use Parabot\BDN\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller {
    /**
     * @Route("/a", name="homepage")
     * @Method({"GET"})
     *
     * @PreAuthorize("isNotBanned()")
     */
    public function indexAction(Request $request) {
        /**
         * @var User $user
         */
        $user = $this->get('security.token_storage')->getToken()->getUser();

        return $this->render(
            'default/index.html.twig',
            [
                'username' => $user->getUsername(),
                'groups'   => $user->getGroupNames(),
            ]
        );
    }

    /**
     * @Route("/b", name="sponsorpage")
     * @Method({"GET"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @PreAuthorize("isSponsor()")
     */
    public function sponsorAction(Request $request) {
        return new JsonResponse([ 'result' => 'Logged in as sponsor' ]);
    }

    /**
     * @Route("/c", name="testpage")
     * @Method({"GET"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @PreAuthorize("isNotBanned()")
     */
    public function testAction(Request $request) {
        $result = $this->get('slack_manager')->inviteToChannel($this->getUser());

        return new JsonResponse($result, $result[ 'code' ]);
    }

    /**
     * @return JsonResponse
     */
    public function homeAction() {
        return new JsonResponse([ "result" => "ok" ]);
    }
}