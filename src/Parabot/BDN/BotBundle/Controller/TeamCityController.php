<?php

namespace Parabot\BDN\BotBundle\Controller;

use AppBundle\Service\SerializerManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TeamCityController extends Controller {
    /**
     * @Route("/build_types/list", name="list_build_types_teamcity")
     * @Method({"GET"})
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listBuildTypesAction(Request $request) {
        $types = $this->get('bot.teamcity.api')->getBuildTypes();

        return new JsonResponse(SerializerManager::normalize($types));
    }

    /**
     * @Route("/builds/list/{projectId}", name="list_builds_teamcity")
     * @Method({"GET"})
     *
     * @param Request $request
     * @param string  $projectId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listBuildsAction(Request $request, $projectId) {
        $builds = $this->get('bot.teamcity.api')->getBuilds($projectId);

        return new JsonResponse(SerializerManager::normalize($builds));
    }
}
