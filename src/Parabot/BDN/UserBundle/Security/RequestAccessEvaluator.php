<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\UserBundle\Security;

use Parabot\BDN\UserBundle\Entity\User;
use Parabot\BDN\UserBundle\Repository\UserRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use JMS\DiExtraBundle\Annotation as DI;

/** @DI\Service */
class RequestAccessEvaluator {

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @DI\InjectParams({
     *     "container" = @DI\Inject("service_container"),
     * })
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    /** @DI\SecurityFunction("isSponsor") */
    public function isSponsor() {
        return ($user = $this->getUser()) != null && $user->hasGroupId(12);
    }

    /**
     * @return User|null
     */
    public function getUser() {
        return ($user = $this->container->get('security.token_storage')->getToken()->getUser(
        )) != null && $user != 'anon.' ? $user : null;
    }

    /** @DI\SecurityFunction("isAdministrator") */
    public function isAdministrator() {
        return ($user = $this->getUser()) != null && $user->hasGroupId(4);
    }

    /** @DI\SecurityFunction("isNotBanned") */
    public function isNotBanned() {
        return ($user = $this->getUser()) != null && ! $user->hasGroupId(23);
    }

    /**
     * @return UserRepository
     */
    private function getUserRepository() {
        return $this->getDoctrineManager()->getRepository('BDNUserBundle:User');
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    private function getDoctrineManager() {
        return $this->container->get('doctrine')->getManager();
    }
}