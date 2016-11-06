<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Entity\Servers\Hooks;

interface GetterHook {
    /**
     * @return array
     */
    public function toGetterArray();
}