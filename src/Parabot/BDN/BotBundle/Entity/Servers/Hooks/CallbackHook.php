<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Entity\Servers\Hooks;

interface CallbackHook {
    /**
     * @return array
     */
    public function toCallbackArray();
}