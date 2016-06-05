<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CronTask;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\Dependencies\ParabotClient;
use AppBundle\Entity\User;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

class DefaultController extends Controller {
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request) {
        return $this->render(
            'default/index.html.twig',
            [
                'base_dir' => realpath($this->container->getParameter('kernel.root_dir') . '/..'),
            ]
        );
    }

    /**
     * @Route("/api/client/build", name="nightly_build")
     * @Method({"POST"})
     */
    public function createNightlyBuild(Request $request) {
        $content = $this->get("request")->getContent();
        if( ! empty($content)) {
            $params = json_decode($content, true);

            if($params[ 'status_message' ] == 'Passed') {
                $client = new ParabotClient();
                $client->setVersion(2.4);
                $client->setCommit($params[ 'commit' ]);
                $client->setName($params[ 'repository' ][ 0 ][ 'name' ]);
                $em = $this->getDoctrine()->getManager();
                $em->persist($client);
                $em->flush();

                return new JsonResponse([ 'result' => 'ok' ]);
            }
        }

        return new JsonResponse([ 'result' => 'error' ]);

    }

    /**
     * @Route("/api/get", name="get_api")
     */
    public function getAPIAction(Request $request) {
        $username = $request->query->get('username');
        $password = $request->query->get('password');

        $user = new User();
        $user->setUsername($username);
        $user->setPassword($password);

        /**
         * @var $manager UserManagerInterface
         * @var $factory EncoderFactory
         */
        $user_manager = $this->get('fos_user.user_manager');
        $factory      = $this->get('security.encoder_factory');

        $user = $user_manager->loadUserByUsername($username);

        $encoder = $factory->getEncoder($user);

        $bool = ($encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt())) ? "true" : "false";

        return new JsonResponse([ $bool ]);
    }

    public function homeAction() {
        return new JsonResponse([ "result" => "ok" ]);
    }

    /**
     * @Route("/test", name="your_examplebundle_crontasks_test")
     */
    public function testAction() {
        $entity = new CronTask();

        $entity->setName('Example asset symlinking task');
        $entity->setInterval(3600);
        $entity->setCommands(
            [
                ' cache:clear',
            ]
        );

        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        $em->flush();

        return new JsonResponse([ 'OK!' ]);
    }
}
