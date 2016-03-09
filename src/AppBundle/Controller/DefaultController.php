<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Dependencies\Client;
use AppBundle\Entity\User;
use FOS\UserBundle\Model\UserManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir') . '/..'),
        ));
    }

    /**
     * @Route("/api/client/build", name="nightly_build")
     * @Method({"POST"})
     */
    public function createNightlyBuild(Request $request){
        $content = $this->get("request")->getContent();
        if (!empty($content)) {
            $params = json_decode($content, true);

            $client = new \AppBundle\Entity\Dependencies\Client();
            $client->setVersion(2.4);
            $client->setCommit($params['after']);
            $client->setName("Parabot Client");

            $em = $this->getDoctrine()->getManager();

            $em->persist($client);
            $em->flush();

            return new JsonResponse(array('result' => 'ok'));
        }

        return new JsonResponse(array('result' => 'error'));

    }

    /**
     * @Route("/api/get", name="get_api")
     */
    public function getAPIAction(Request $request){
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
        $factory = $this->get('security.encoder_factory');

        $user = $user_manager->loadUserByUsername($username);

        $encoder = $factory->getEncoder($user);

        $bool = ($encoder->isPasswordValid($user->getPassword(),$password,$user->getSalt())) ? "true" : "false";

        return new JsonResponse(array($bool));
    }
}
