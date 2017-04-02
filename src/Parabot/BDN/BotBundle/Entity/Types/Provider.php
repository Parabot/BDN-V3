<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Entity\Types;

use Doctrine\ORM\Mapping as ORM;
use Parabot\BDN\BotBundle\Entity\Servers\Server;

/**
 * Provider
 *
 * @ORM\Table(name="type_provider")
 * @ORM\Entity(repositoryClass="Parabot\BDN\BotBundle\Repository\ProviderRepository")
 */
class Provider extends Type {

    /**
     * @var Server[]
     *
     * @ORM\OneToMany(targetEntity="Parabot\BDN\BotBundle\Entity\Servers\Server", mappedBy="provider")
     */
    private $servers;

    /**
     * Client constructor.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * @return Server[]
     */
    public function getServers() {
        return $this->servers;
    }

    /**
     * @param Server[] $servers
     */
    public function setServers($servers) {
        $this->servers = $servers;
    }

    public function getType() {
        return 'Provider';
    }

    /**
     * @return string
     */
    public function getTravisRepository() {
        return 'Parabot/Parabot-317-API-Minified';
    }

    /**
     * @return string
     */
    public function getName() {
        return 'Provider';
    }
}