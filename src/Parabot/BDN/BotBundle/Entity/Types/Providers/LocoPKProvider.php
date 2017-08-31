<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Entity\Types\Providers;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class LocoPKProvider
 *
 * @ORM\Table(name="type_locopk_provider")
 * @ORM\Entity(repositoryClass="Parabot\BDN\BotBundle\Repository\ProviderRepository")
 */
class LocoPKProvider extends Provider {
    public function getType() {
        return 'LocoPK-Provider';
    }

    public function getTravisRepository() {
        return 'Parabot/Parabot-317-API-Minified-LocoPK';
    }

    public function getName() {
        return 'LocoPK-Provider';
    }
}