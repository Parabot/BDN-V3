<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Entity\Types;

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