<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Entity\Types\Providers;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class IkovProvider
 *
 * @ORM\Table(name="type_ikov_provider")
 * @ORM\Entity(repositoryClass="Parabot\BDN\BotBundle\Repository\ProviderRepository")
 */
class IkovProvider extends Provider {
    public function getType() {
        return 'Ikov-Provider';
    }

    public function getTravisRepository() {
        return 'JKetelaar/Parabot-317-API-Minified-Ikov';
    }

    public function getName() {
        return 'Ikov-Provider';
    }
}
