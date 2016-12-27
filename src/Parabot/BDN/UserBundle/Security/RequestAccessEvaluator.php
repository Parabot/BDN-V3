<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\UserBundle\Security;

use JMS\DiExtraBundle\Annotation as DI;
use Parabot\BDN\UserBundle\Entity\User;
use Parabot\BDN\UserBundle\Repository\UserRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
        return ($user = $this->getUser()) != null && $user->hasGroupId(12, true);
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
        return ($user = $this->getUser()) != null && $user->hasGroupId(4, true);
    }

    /** @DI\SecurityFunction("isNotBanned") */
    public function isNotBanned() {
        return ($user = $this->getUser()) != null && ! $user->hasGroupId(23, true);
    }

    /** @DI\SecurityFunction("isServerDeveloper") */
    public function isServerDeveloper() {
        return ($user = $this->getUser()) != null && $user->hasGroupId(22, true);
    }

    /**
     * @DI\SecurityFunction("isScriptWriter")
     *
     * @param User|null $user
     *
     * @return bool
     */
    public function isScriptWriter(User $user = null) {
        if ($user === null){
            $user = $this->getUser();
        }
        return $user != null && $user->hasGroupId(9, true);
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