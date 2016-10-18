<?php

namespace Parabot\BDN\BotBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Parabot\BDN\BotBundle\Entity\Script;
use Parabot\BDN\BotBundle\Entity\Scripts\Git;
use Parabot\BDN\UserBundle\Entity\User;

/**
 * ScriptRepository
 */
class ScriptRepository extends EntityRepository {

    /**
     * @param int $id
     *
     * @return null|Git
     */
    public function findOneGitById($id) {
        /** @var Script $result */
        $result = $this->findOneBy([ 'id' => $id ]);
        if($result != null) {
            if(($git = $result->getGit()) != null) {
                return $result->getGit();
            }
        }

        return null;
    }

    /**
     * @param User $user
     *
     * @return Script[]
     */
    public function findByAuthor($user) {
        $query = $this->getEntityManager()->createQuery(
            'SELECT s FROM BDNBotBundle:Script s JOIN s.authors a WHERE a.id = :id AND s.active = 1'
        )->setParameter('id', $user->getId());

        return $query->getResult();
    }

    public function findScriptsForUser(User $user, $scriptWriters) {
        if( ! is_array($scriptWriters)) {
            $scriptWriters = [ $scriptWriters ];
        }

        $query = $this->createQueryBuilder('s')->leftJoin('s.users', 'user')->leftJoin(
                's.creator',
                'creator'
            )->leftJoin('creator.groups', 'groups')->where('user.id = :id')->andWhere(
                'groups.id IN (:gids)'
            )->setParameter('id', $user->getId())->setParameter('gids', $scriptWriters)->getQuery();

        return $query->getResult();
    }
}
