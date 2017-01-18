<?php

namespace Parabot\BDN\BotBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Parabot\BDN\BotBundle\Entity\Script;
use Parabot\BDN\BotBundle\Entity\Scripts\Review;
use Parabot\BDN\UserBundle\Entity\User;

/**
 * ReviewRepository
 */
class ReviewRepository extends EntityRepository {

    /**
     * @param Script $script
     * @param User   $user
     *
     * @return Review|array
     */
    public function getReview(Script $script, User $user) {
        $result = $this->createQueryBuilder('reviewRepository')
            ->select('r')
            ->from('BDNBotBundle:Scripts\Review', 'r')
            ->innerJoin('r.script', 's')
            ->innerJoin('r.user', 'u')
            ->where('u.id = :uid')
            ->andWhere('s.id = :sid')
            ->setParameter('uid', $user->getId())
            ->setParameter('sid', $script->getId())
            ->getQuery()
            ->getResult();

        if($result != null && is_array($result) && count($result) > 0) {
            return $result[ 0 ];
        }

        return $result;
    }
}
