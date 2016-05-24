<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Parabot\BDN\BotBundle\Entity\Types\Client;

class ClientRepository extends EntityRepository {

    /**
     * @param boolean $stable
     *
     * @return Client[]
     */
    public function findAllByStability($stable) {
        return $this->findBy([ 'stable' => $stable ]);
    }

    public function findLatestByStability($stable) {
        $clients = $this->findBy([ 'stable' => $stable ], [ 'releaseDate' => 'DESC' ]);
        if($clients != null) {
            return $clients[ 0 ];
        }

        return null;
    }
}