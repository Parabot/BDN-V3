<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Entity\Types\Providers;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class DreamScapeProvider
 *
 * @ORM\Table(name="type_dreamscape_provider")
 * @ORM\Entity(repositoryClass="Parabot\BDN\BotBundle\Repository\ProviderRepository")
 */
class DreamScapeProvider extends Provider {
    public function getType() {
        return 'DreamScape-Provider';
    }

    public function getTravisRepository() {
        return 'Parabot/Parabot-317-API-Minified-Dreamscape';
    }

    public function getName() {
        return 'DreamScape-Provider';
    }
}