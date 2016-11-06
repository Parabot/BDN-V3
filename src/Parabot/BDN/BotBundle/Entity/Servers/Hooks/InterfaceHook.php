<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Entity\Servers\Hooks;

interface InterfaceHook {
    /**
     * @return array
     */
    public function toInterfaceArray();
}