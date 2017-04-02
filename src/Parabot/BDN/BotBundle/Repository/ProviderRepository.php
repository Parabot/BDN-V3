<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Parabot\BDN\BotBundle\Entity\Types\OSScapeProvider;
use Parabot\BDN\BotBundle\Entity\Types\Provider;
use Parabot\BDN\BotBundle\Entity\Types\Type;
use Symfony\Component\HttpFoundation\Request;

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
     * @param boolean      $stable
     *
     * @param null|string  $branch
     *
     * @param Request|null $request
     *
     * @return null|Type
     */
    public function findLatestByStability($stable, $branch = null, Request $request = null) {
        $findBy = [ 'stable' => $stable ];
        if($branch != null) {
            $findBy[ 'branch' ] = $branch;
        }
        $clients = $this->findBy($findBy, [ 'releaseDate' => 'DESC' ]);
        if($clients != null) {
            if(($server = $request->get('server')) !== null && $server == 'OS-Scape') {
                /**
                 * @var Provider $s
                 */
                $s = $clients[0];
                $p = new OSScapeProvider();
                $p->setPath($s->getPath());
                $p->setVersion(1);
                return $p;
            } else {
                return $clients[ 0 ];
            }
        }

        return null;
    }
}