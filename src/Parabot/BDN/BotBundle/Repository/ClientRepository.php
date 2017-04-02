<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Parabot\BDN\BotBundle\Entity\Types\Client;
use Symfony\Component\HttpFoundation\Request;

class ClientRepository extends EntityRepository implements TypeRepository {

    /**
     * @param boolean $stable
     *
     * @return Client[]
     */
    public function findAllByStability($stable) {
        return $this->findBy([ 'stable' => $stable ]);
    }

    /**
     * @param boolean      $stable
     *
     * @param null|string  $branch
     *
     * @param Request|null $request
     *
     * @return null|Client
     */
    public function findLatestByStability($stable, $branch = null, Request $request = null) {
        $findBy = [ 'stable' => $stable ];
        if($branch != null) {
            $findBy[ 'branch' ] = $branch;
        }
        $clients = $this->findBy($findBy, [ 'releaseDate' => 'DESC' ]);
        if($clients != null) {
            return $clients[ 0 ];
        }

        return null;
    }
}