<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Parabot\BDN\BotBundle\Entity\Types\Type;

class ProviderRepository extends EntityRepository implements TypeRepository {

    /**
     * @param boolean $stable
     *
     * @return Type[]
     */
    public function findAllByStability($stable) {
        return $this->findBy([ 'stable' => $stable ]);
    }

    /**
     * @param boolean     $stable
     *
     * @param null|string $branch
     *
     * @return null|Type
     */
    public function findLatestByStability($stable, $branch = null) {
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