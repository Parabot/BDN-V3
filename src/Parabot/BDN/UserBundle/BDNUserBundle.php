<?php

namespace Parabot\BDN\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class BDNUserBundle extends Bundle {
    public function getParent() {
        return 'FOSUserBundle';
    }
}
