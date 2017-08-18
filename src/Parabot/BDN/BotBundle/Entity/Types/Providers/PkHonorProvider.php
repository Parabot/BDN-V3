<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Entity\Types\Providers;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class PkHonorProvider
 *
 * @ORM\Table(name="type_pkhonor_provider")
 * @ORM\Entity(repositoryClass="Parabot\BDN\BotBundle\Repository\ProviderRepository")
 */
class PkHonorProvider extends Provider {
    public function getType() {
        return 'PkHonor-Provider';
    }

    public function getTravisRepository() {
        return 'Parabot/Parabot-317-API-Minified-PkHonor';
    }

    public function getName() {
        return 'PkHonor-Provider';
    }
}