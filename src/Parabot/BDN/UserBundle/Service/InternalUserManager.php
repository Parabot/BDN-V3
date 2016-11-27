<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\UserBundle\Service;

use Doctrine\ORM\EntityManager;
use Parabot\BDN\UserBundle\Entity\User;
use Parabot\BDN\UserBundle\Repository\UserRepository;

class InternalUserManager {

    /**
     * @var UserRepository $userRepository
     */
    private $userRepository;

    /**
     * InternalUserManager constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager) {
        $this->userRepository = $entityManager->getRepository('BDNUserBundle:User');
    }


    /**
     * @param string $apiKey
     *
     * @return User|null
     */
    public function getUser($apiKey) {
        if($apiKey != null) {
            $user = $this->userRepository->findOneBy([ 'apiKey' => $apiKey ]);
            if($user !== null) {
                return $user;
            }
        }

        return null;
    }
}