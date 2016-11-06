<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Entity\Servers\Hooks;

interface InvokerHook {
    /**
     * @return array
     */
    public function toInvokerArray();
}