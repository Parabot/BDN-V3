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
     * Client constructor.
     */
    public function __construct() {
        parent::__construct();
    }

    public function getType() {
        return 'Client';
    }

    /**
     * @return string
     */
    public function getTravisRepository() {
        return 'Parabot/Parabot';
    }

    /**
     * @return string
     */
    public function getName() {
        return 'Parabot';
    }
}
