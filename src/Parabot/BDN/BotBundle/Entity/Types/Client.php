<?php

namespace Parabot\BDN\BotBundle\Entity\Types;

use Doctrine\ORM\Mapping as ORM;

/**
 * Client
 *
 * @ORM\Table(name="type_client")
 * @ORM\Entity(repositoryClass="Parabot\BDN\BotBundle\Repository\ClientRepository")
 */
class Client extends Type {

    /**
     * @ORM\Column(name="stable", type="boolean")
     * 
     * @var boolean
     */
    private $stable;

    /**
     * Get stability
     * 
     * @return boolean
     */
    public function getStable() {
        return $this->stable;
    }

    /**
     * @param boolean $stable
     */
    public function setStable($stable) {
        $this->stable = $stable;
    }
}
