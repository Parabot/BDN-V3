<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\UserBundle\Service;

use Doctrine\ORM\EntityManager;
use Parabot\BDN\UserBundle\Entity\User;
use Parabot\BDN\UserBundle\Repository\UserRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class InternalUserManager {

    /**
     * @var UserRepository $userRepository
     */
    private $userRepository;

    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * InternalUserManager constructor.
     *
     * @param EntityManager $entityManager
     * @param TokenStorage  $tokenStorage
     */
    public function __construct(EntityManager $entityManager, TokenStorage $tokenStorage) {
        $this->userRepository = $entityManager->getRepository('BDNUserBundle:User');
        $this->tokenStorage   = $tokenStorage;
    }


    /**
     * @param string|null $apiKey
     *
     * @return User|null
     */
    public function getUser($apiKey = null) {
        if($apiKey != null) {
            $user = $this->userRepository->findOneBy([ 'apiKey' => $apiKey ]);
            if($user !== null) {
                return $user;
            }
        } else {
            $user = $this->tokenStorage->getToken()->getUser();
            if($user !== null) {
                return $user;
            }
        }

        return null;
    }
}