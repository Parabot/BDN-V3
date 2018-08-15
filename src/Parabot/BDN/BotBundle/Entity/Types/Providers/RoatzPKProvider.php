<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Entity\Types\Providers;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class RoatzPKProvider
 *
 * @ORM\Table(name="type_roatzpk_provider")
 * @ORM\Entity(repositoryClass="Parabot\BDN\BotBundle\Repository\ProviderRepository")
 */
class RoatzPKProvider extends Provider {
    public function getType() {
        return 'RoatzPK-Provider';
    }

    public function getTravisRepository() {
        return 'Parabot/Parabot-317-API-Minified-RoatzPK';
    }

    public function getName() {
        return 'RoatzPK-Provider';
    }
}