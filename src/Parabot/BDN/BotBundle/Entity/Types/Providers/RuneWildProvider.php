<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Entity\Types\Providers;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class RuneWildProvider
 *
 * @ORM\Table(name="type_runewild_provider")
 * @ORM\Entity(repositoryClass="Parabot\BDN\BotBundle\Repository\ProviderRepository")
 */
class RuneWildProvider extends Provider {
    public function getType() {
        return 'RuneWild-Provider';
    }

    public function getTravisRepository() {
        return 'Parabot/Parabot-317-API-Minified-RuneWild';
    }

    public function getName() {
        return 'RuneWild-Provider';
    }
}