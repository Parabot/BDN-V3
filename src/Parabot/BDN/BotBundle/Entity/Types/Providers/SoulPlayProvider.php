<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Entity\Types\Providers;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class SoulPlayProvider
 * @package Parabot\BDN\BotBundle\Entity\Types\Providers
 *
 * @ORM\Table(name="type_soulplay_provider")
 * @ORM\Entity(repositoryClass="Parabot\BDN\BotBundle\Repository\ProviderRepository")
 */
class SoulPlayProvider extends Provider {
    public function getType() {
        return 'SoulPlay-Provider';
    }

    public function getTravisRepository() {
        return 'Parabot/Parabot-317-API-Minified-SoulPlay';
    }

    public function getName() {
        return 'SoulPlay-Provider';
    }
}