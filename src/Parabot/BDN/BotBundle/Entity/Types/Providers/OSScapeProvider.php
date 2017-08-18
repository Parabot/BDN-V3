<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Entity\Types\Providers;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class OSScapeProvider
 *
 * @ORM\Table(name="type_osscape_provider")
 * @ORM\Entity(repositoryClass="Parabot\BDN\BotBundle\Repository\ProviderRepository")
 */
class OSScapeProvider extends Provider {
    public function getType() {
        return 'OS-Scape-Provider';
    }

    public function getTravisRepository() {
        return 'Parabot/Parabot-317-API-Minified-OS-Scape';
    }

    public function getName() {
        return 'OS-Scape-Provider';
    }
}