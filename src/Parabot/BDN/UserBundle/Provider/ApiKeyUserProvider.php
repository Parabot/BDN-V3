<?php
/**
 * @author JKetelaar
 */
namespace Parabot\BDN\UserBundle\Provider;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class ApiKeyUserProvider implements UserProviderInterface {

    /**
     * @var EntityManager
     */
    private $manager;

    /**
     * ApiKeyUserProvider constructor.
     *
     * @param EntityManager $manager
     */
    public function __construct($manager) {
        $this->manager = $manager;
    }

    public function getUsernameForApiKey($apiKey) {
        $repository = $this->manager->getRepository('BDNUserBundle:User');
        $user       = $repository->findOneBy([ 'apiKey' => $apiKey ]);
        if($user != null) {
            return $user->getUsername();
        } else {
            return null;
        }
    }

    public function loadUserByUsername($username) {
        $repository = $this->manager->getRepository('BDNUserBundle:User');

        return $repository->findOneBy([ 'username' => $username ]);
    }

    public function refreshUser(UserInterface $user) {
        throw new UnsupportedUserException();
    }

    public function supportsClass($class) {
        return 'Symfony\Component\Security\Core\User\User' === $class;
    }
}