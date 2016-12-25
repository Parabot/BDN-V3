<?php

namespace AppBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use Parabot\BDN\BotBundle\Entity\Script;
use Parabot\BDN\BotBundle\Entity\Scripts\Git;
use Parabot\BDN\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     */
    public function testAction(Request $request) {

        return new JsonResponse([ 'result', $request->get('asd') ]);
    }

    /**
     * @Route("/script", name="scriptpage")
     * @Method({"GET"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function testScriptAction(Request $request) {
        $script = new Script();
        $script->setName('Lord Peng');

        $author  = $this->getDoctrine()->getRepository('BDNUserBundle:User')->findAll()[ 0 ];
        $authors = [ $author ];
        $script->setAuthors($authors);

        $script->setCategories([]);
        $script->setDescription('Amazing!');
        $script->setGroups([]);
        $script->setVersion(1.0);
        $script->setCreator($author);

        $git = new Git();
        $git->setUrl('git@github.com:JKetelaar/LordPeng.git');
        $script->setGit($git);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($git);
        $manager->persist($script);
        $manager->flush();

        die();

        return new JsonResponse([ '$result', $request->get('asd') ]);
    }

    /**
     * @return RedirectResponse
     */
    public function docsAction() {
        return new RedirectResponse('/docs/index.html', 301);
    }

    /**
     * @return JsonResponse
     */
    public function homeAction() {
        return new JsonResponse([ "result" => "ok" ]);
    }
}