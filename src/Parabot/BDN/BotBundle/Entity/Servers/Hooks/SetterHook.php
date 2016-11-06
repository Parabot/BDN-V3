<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Entity\Servers\Hooks;

interface SetterHook {
    /**
     * @return array
     */
    public function toSetterArray();
}