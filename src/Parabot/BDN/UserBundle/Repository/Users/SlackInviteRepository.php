<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\UserBundle\Repository\Users;

use Doctrine\ORM\EntityRepository;
use Parabot\BDN\UserBundle\Entity\User;
use Parabot\BDN\UserBundle\Entity\Users\SlackInvite;

class SlackInviteRepository extends EntityRepository {

    /**
     * @param User $user
     *
     * @return SlackInvite[]
     */
    public function findByUser(User $user) {
        $query = $this->getEntityManager()->createQuery(
            'SELECT s FROM BDNUserBundle:Users\SlackInvite s JOIN s.user a WHERE a.id = :id'
        )->setParameter('id', $user->getId());

        return $query->getResult();
    }
}