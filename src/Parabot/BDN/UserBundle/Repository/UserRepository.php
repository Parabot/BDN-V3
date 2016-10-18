<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\UserBundle\Repository;

use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityRepository;
use Parabot\BDN\UserBundle\Entity\User;

class UserRepository extends EntityRepository {

    /**
     * @param int $id
     *
     * @return null|User
     */
    public function getUserByCommunityMemberId($id) {
        $result = $this->createQueryBuilder('u')->leftJoin('u.communityUser', 'uc')->where(
                'uc.member_id = :id'
            )->setMaxResults(1)->setParameter('id', $id);

        /**
         * @var User[] $users
         */
        if(($users = $result->getQuery()->getResult()) != null && is_array($users) && sizeof($users) > 0) {
            return $users[ 0 ];
        }

        return null;
    }
}