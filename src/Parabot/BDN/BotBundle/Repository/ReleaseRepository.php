<?php

namespace Parabot\BDN\BotBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Parabot\BDN\BotBundle\Entity\Script;
use Parabot\BDN\BotBundle\Entity\Scripts\Release;

/**
 * ReleaseRepository
 */
class ReleaseRepository extends EntityRepository {

    /**
     * @param Script $script
     *
     * @return array|Release
     */
    public function getLatestRelease(Script $script) {
        $result = $this->createQueryBuilder('releaseRepository')->select('r')->from(
                'BDNBotBundle:Scripts\Release',
                'r'
            )->innerJoin('r.script', 's')->where('s.id = :sid')->orderBy('r.date', 'DESC')->setParameter(
                'sid',
                $script->getId()
            )->getQuery()->getResult();

        if($result != null && is_array($result) && count($result) > 0) {
            return $result[ 0 ];
        }

        return $result;
    }
}
