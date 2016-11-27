<?php

namespace Parabot\BDN\OAuthServerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class BDNOAuthServerBundle extends Bundle {
    public function getParent() {
        return 'FOSOAuthServerBundle';
    }
}
