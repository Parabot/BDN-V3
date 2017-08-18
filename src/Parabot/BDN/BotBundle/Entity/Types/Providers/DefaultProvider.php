<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Entity\Types\Providers;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="type_default_provider")
 * @ORM\Entity(repositoryClass="Parabot\BDN\BotBundle\Repository\ProviderRepository")
 */
class DefaultProvider extends Provider {
    public function getTravisRepository() {
        return 'Parabot/Parabot-317-API-Minified';
    }

    public function getName() {
        return 'Provider';
    }

    public function getType() {
        return 'Provider';
    }
}