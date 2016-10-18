<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Repository;

use Parabot\BDN\BotBundle\Entity\Types\Type;

interface TypeRepository {

    /**
     * @param boolean $stable
     *
     * @return Type[]
     */
    public function findAllByStability($stable);

    /**
     * @param boolean     $stable
     *
     * @param null|string $branch
     *
     * @return null|Type
     */
    public function findLatestByStability($stable, $branch = null);
}