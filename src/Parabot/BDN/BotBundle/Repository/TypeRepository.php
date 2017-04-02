<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Repository;

use Parabot\BDN\BotBundle\Entity\Types\Type;
use Symfony\Component\HttpFoundation\Request;

interface TypeRepository {

    /**
     * @param boolean $stable
     *
     * @return Type[]
     */
    public function findAllByStability($stable);

    /**
     * @param boolean      $stable
     *
     * @param null|string  $branch
     *
     * @param Request|null $request
     *
     * @return null|Type
     */
    public function findLatestByStability($stable, $branch = null, Request $request = null);
}