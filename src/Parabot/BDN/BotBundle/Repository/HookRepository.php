<?php

namespace Parabot\BDN\BotBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Parabot\BDN\BotBundle\Entity\Servers\Server;

/**
 * HookRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class HookRepository extends EntityRepository {

    /**
     * @param Server     $server
     *
     * @param float|null $version
     *
     * @return \Parabot\BDN\BotBundle\Entity\Servers\Hook[]
     */
    public function findHooksByServer(Server $server, $version = null) {
        $query = $this->getEntityManager()->createQuery(
            'SELECT s FROM BDNBotBundle:Servers\Hook s JOIN s.server a WHERE a.id = :id AND s.version = :version'
        )->setParameter('id', $server->getId())->setParameter(
            'version',
            $version != null ? $version : $server->getVersion()
        );

        return $query->getResult();
    }
}
