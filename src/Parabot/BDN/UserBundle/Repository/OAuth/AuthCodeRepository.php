<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\UserBundle\Repository\OAuth;

use Doctrine\ORM\EntityRepository;
use Parabot\BDN\UserBundle\Entity\User;

class AuthCodeRepository extends EntityRepository {

    /**
     * @param User $user
     *
     * @return bool
     */
    public function hasGivenAccess(User $user) {
        $result = $this->createQueryBuilder('a')->leftJoin('a.user', 'au')->where(
            'au.id = :id'
        )->setMaxResults(1)->setParameter('id', $user->getId());

        /**
         * @var User[] $users
         */
        if(($users = $result->getQuery()->getResult()) != null && is_array($users) && sizeof($users) > 0) {
            return false; // Temp fix, as this doesn't have the ability to check the oauth client yet
        }

        return false;
    }
}