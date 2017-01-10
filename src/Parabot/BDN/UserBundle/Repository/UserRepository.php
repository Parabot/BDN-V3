<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Parabot\BDN\UserBundle\Entity\OAuth\Client;
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

    public function countTotal() {
        $query = $this->createQueryBuilder('user')->select('count(user.id)');

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getForPage($page = 1, $limit = 25) {
        $query = $this->createQueryBuilder('user')->setMaxResults($limit)->setFirstResult(($page - 1) * $limit);

        return $query->getQuery()->getResult();
    }

    public function countSearchByUsername($username) {
        $query = $this->createQueryBuilder('user')->select('count(user.id)');
        $query = $query->where('user.username LIKE :username');

        $query->setParameter(
            'username',
            '%' . $username . '%'
        );

        return $query->getQuery()->getSingleScalarResult();
    }

    public function hasGivenOauthClientAccess(User $user, Client $client){
        foreach($user->getClientAccesses() as $c){
            if ($c->getId() === $client->getId()){
                return true;
            }
        }
        return false;
    }

    public function searchByUsername($username, $page = 1, $limit = 25) {
        $query = $this->createQueryBuilder('user')->where('user.username LIKE :username');

        $query->setParameter(
            'username',
            '%' . $username . '%'
        );
        $query->setMaxResults($limit);
        $query->setFirstResult(($page - 1) * $limit);

        $query = $query->getQuery();

        return $query->getResult();
    }
}