<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Controller;

use AppBundle\Service\SerializerManager;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ScriptController extends Controller {

    /**
     * @Route("/list/my", name="list_my_scripts")
     * @Method({"GET"})
     *
     * @PreAuthorize("isScriptWriter()")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function listMyAction(Request $request) {
        $sRepository = $this->getDoctrine()->getRepository('BDNBotBundle:Script');
        $user        = $this->get('request_access_evaluator')->getUser();

        $scripts = $sRepository->findByAuthor($user);

        return new JsonResponse([ 'result' => SerializerManager::normalize($scripts) ]);
    }

    /**
     * @Route("/list/author/{username}")
     *
     * @param Request $request
     * @param string  $username
     *
     * @return JsonResponse
     */
    public function listAuthorAction(Request $request, $username) {
        $sRepository = $this->getDoctrine()->getRepository('BDNBotBundle:Script');
        $uRepository = $this->getDoctrine()->getRepository('BDNUserBundle:User');

        if(($user = $uRepository->findOneBy([ 'username' => $username ])) != null) {
            $scripts = $sRepository->findByAuthor($user);

            $scriptsResult = [];
            foreach($scripts as $script) {
                $authors = [];
                foreach($script->getAuthors() as $author) {
                    $authors[] = [
                        'id'       => $author->getId(),
                        'username' => $author->getUsername(),
                    ];
                }
                $scriptsResult[] = [
                    'id'          => $script->getId(),
                    'name'        => $script->getName(),
                    'authors'     => $authors,
                    'description' => $script->getDescription(),
                    'version'     => $script->getVersion(),
                ];
            }

            return new JsonResponse([ 'result' => $scriptsResult ]);
        } else {
            return new JsonResponse([ 'result' => 'No user found with that username' ], 404);
        }
    }
}