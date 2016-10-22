<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Entity\Types;

use Doctrine\ORM\Mapping as ORM;

/**
 * Provider
 *
 * @ORM\Table(name="type_provider")
 * @ORM\Entity(repositoryClass="Parabot\BDN\BotBundle\Repository\ProviderRepository")
 */
class Provider extends Type {

    /**
     * Client constructor.
     */
    public function __construct() {
        parent::__construct();
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