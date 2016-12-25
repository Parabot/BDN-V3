<?php

namespace Parabot\BDN\BotBundle\Controller;

use AppBundle\Service\SerializerManager;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use Parabot\BDN\UserBundle\Entity\User;
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
     * @PreAuthorize("isScriptWriter()")
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
     * @Route("/builds/list/{scriptId}", name="list_builds_teamcity")
     * @Method({"GET"})
     *
     * @PreAuthorize("isScriptWriter()")
     *
     * @param Request $request
     * @param string  $scriptId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listBuildsAction(Request $request, $scriptId) {
        $access = $this->isValidScript($scriptId, 'id');
        if($access !== true) {
            return $access;
        }
        $script = $this->getDoctrine()->getRepository('BDNBotBundle:Script')->findOneBy([ 'id' => $scriptId ]);

        $builds = $this->get('bot.teamcity.api')->getBuilds($script);

        return new JsonResponse([ 'builds' => SerializerManager::normalize($builds), 'script' => $script->getName() ]);
    }

    private function isValidScript($id, $find = 'id') {
        $script = $this->getDoctrine()->getRepository('BDNBotBundle:Script')->findOneBy([ $find => $id ]);
        $user   = $this->get('request_access_evaluator')->getUser();

        if($script == null) {
            return new JsonResponse([ 'result' => 'Unknown script requested' ], 404);
        }

        /**
         * @var User $u
         */
        foreach($script->getAuthors() as $u) {
            if($u->getId() === $user->getId()) {
                return true;
            }
        }

        return new JsonResponse([ 'result' => 'User does not have access to script' ], 403);
    }

    /**
     * @Route("/builds/get/{buildId}", name="get_build_teamcity")
     * @Method({"GET"})
     *
     * @PreAuthorize("isScriptWriter()")
     *
     * @param Request $request
     * @param int     $buildId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getBuildAction(Request $request, $buildId) {
        $build = $this->get('bot.teamcity.api')->getBuild($buildId)[ 0 ];
        $log   = $this->get('bot.teamcity.api')->getBuildLog($buildId);

        return new JsonResponse([ 'build' => SerializerManager::normalize($build), 'log' => $log ]);
    }

    /**
     * @Route("/build_types/create/{buildTypeId}", name="create_build_type_teamcity")
     * @Method({"GET"})
     *
     * @PreAuthorize("isScriptWriter()")
     *
     * @param Request $request
     * @param         $buildTypeId
     *
     * @return JsonResponse
     */
    public function createBuild(Request $request, $buildTypeId) {
        $access = $this->isValidScript($buildTypeId, 'buildTypeId');
        if($access !== true) {
            return $access;
        }

        $created = $this->get('bot.teamcity.api')->startBuild($buildTypeId);
        $script  = $this->getDoctrine()->getRepository('BDNBotBundle:Script')->findOneBy(
            [ 'buildTypeId' => $buildTypeId ]
        );

        if($created === true && $script != null) {
            $this->get('slack_manager')->sendSuccessMessage(
                'Script build created',
                'Build created for script ' . $script->getName(),
                '',
                [],
                '#depoyments'
            );
        }

        return new JsonResponse([ 'result' => $created ]);
    }

    /**
     * @Route("/projects/create/{scriptId}", name="create_project_teamcity")
     * @Method({"POST"})
     *
     * @PreAuthorize("isScriptWriter()")
     *
     * @param Request $request
     * @param int     $scriptId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createProjectAction(Request $request, $scriptId) {
        $access = $this->isValidScript($scriptId, 'id');
        if($access !== true) {
            return $access;
        }

        $script  = $this->getDoctrine()->getRepository('BDNBotBundle:Script')->findOneBy(
            [ 'id' => $scriptId ]
        );
        $manager = $this->getDoctrine()->getManager();

        $created        = false;
        $projectCreated = $this->get('bot.teamcity.api')->createProject($script);

        if($projectCreated === true && $request->get('modules') == 'all') {
            $VCSCreated   = $this->get('bot.teamcity.api')->createVSC($script);
            $BuildCreated = ($buildTypeID = $this->get('bot.teamcity.api')->createBuildType($script)) !== null;
            if($buildTypeID !== null) {
                $script->setBuildTypeId($buildTypeID);
                $manager->persist($script);
                $manager->flush();
            }

            if($VCSCreated === true && $BuildCreated === true) {
                $created = true;
            }
        } else {
            $created = true;
        }

        return new JsonResponse([ 'result' => $created ]);
    }

    /**
     * @Route("/vcs/create/{scriptId}", name="create_vcs_teamcity")
     * @Method({"POST"})
     *
     * @PreAuthorize("isScriptWriter()")
     *
     * @param Request $request
     * @param int     $scriptId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createVCSAction(Request $request, $scriptId) {
        $access = $this->isValidScript($scriptId, 'id');
        if($access !== true) {
            return $access;
        }

        $script = $this->getDoctrine()->getRepository('BDNBotBundle:Script')->findOneBy(
            [ 'id' => $scriptId ]
        );

        $created = $this->get('bot.teamcity.api')->createVSC($script);

        return new JsonResponse([ 'result' => $created ]);
    }
}
