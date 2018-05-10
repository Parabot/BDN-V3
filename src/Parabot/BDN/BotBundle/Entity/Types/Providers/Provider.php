<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Entity\Types\Providers;

use Doctrine\ORM\Mapping as ORM;
use Parabot\BDN\BotBundle\Entity\Servers\Server;
use Parabot\BDN\BotBundle\Entity\Types\Type;

/**
 * Sort-of-abstract Provider
 *
 * @ORM\MappedSuperclass
 */
class Provider extends Type
{
    /**
     * @var Server[]
     *
     * @ORM\OneToMany(targetEntity="Parabot\BDN\BotBundle\Entity\Servers\Server", mappedBy="provider")
     */
    protected $servers;

    /**
     * Client constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return Server[]
     */
    public function getServers()
    {
        return $this->servers;
    }

    /**
     * @param Server[] $servers
     */
    public function setServers($servers)
    {
        $this->servers = $servers;
    }

    public function getType()
    {
        return 'Provider';
    }

    /**
     * @return string
     */
    public function getTravisRepository()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Provider';
    }
}