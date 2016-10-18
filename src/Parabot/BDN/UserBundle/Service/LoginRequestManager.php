<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\UserBundle\Service;

use AppBundle\Service\StringUtils;
use Doctrine\ORM\EntityManager;
use Parabot\BDN\UserBundle\Entity\Login\RequestToken;

class LoginRequestManager {

    const KEY_COOKIE = 'KEY_OAUTH';

    /**
     * @var EntityManager $entityManager
     */
    private $entityManager;

    /**
     * LoginRequestManager constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager) { $this->entityManager = $entityManager; }

    /**
     * @return bool|string
     */
    public function insertRequest() {
        $repository = $this->entityManager->getRepository('BDNUserBundle:Login\RequestToken');

        $key   = StringUtils::generateRandomString(20, false);
        $tries = 0;
        while(($result = $repository->findOneBy([ 'key' => $key ])) != null && $tries < 20) {
            $key = StringUtils::generateRandomString(20, false);
            $tries++;
        }
        if($tries >= 20) {
            return false;
        }

        $token = new RequestToken();
        $token->setKey($key);

        $this->entityManager->persist($token);
        $this->entityManager->flush();

        return $key;
    }

    public function assignUserToKey($key, $user) {
        $repository = $this->entityManager->getRepository('BDNUserBundle:Login\RequestToken');
        $token      = $repository->findOneBy([ 'key' => $key ]);

        if($token !== null && $token->getUser() === null) {
            $token->setUser($user);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }

    /**
     * @param $key
     *
     * @return bool|string
     */
    public function retrieveUserApiFromKey($key) {
        $repository = $this->entityManager->getRepository('BDNUserBundle:Login\RequestToken');
        $token      = $repository->findOneBy([ 'key' => $key ]);

        if($token !== null) {
            $user = $token->getUser();
            if($user !== null) {
                return $user->getApiKey();
            }
        }

        return false;
    }
}