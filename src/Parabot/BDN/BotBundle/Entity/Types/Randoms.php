<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Entity\Types;

use Doctrine\ORM\Mapping as ORM;

/**
 * Randoms
 *
 * @ORM\Table(name="type_randoms")
 * @ORM\Entity(repositoryClass="Parabot\BDN\BotBundle\Repository\RandomsRepository")
 */
class Randoms extends Type {

    /**
     * @return string
     */
    public function getType() {
        return 'Randoms';
    }

    /**
     * @return string
     */
    public function getTravisRepository() {
        return 'Parabot/Randoms';
    }

    /**
     * @return string
     */
    public function getName() {
        return 'Randoms';
    }
}