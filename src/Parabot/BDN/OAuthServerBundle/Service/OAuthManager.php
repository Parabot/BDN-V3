<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\OAuthServerBundle\Service;

use OAuth2\OAuth2;
use Parabot\BDN\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class OAuthManager {

    /**
     * @var OAuth2 $oauth2
     */
    private $oauth2;

    /**
     * OAuthManager constructor.
     *
     * @param OAuth2 $oauth2
     */
    public function __construct(OAuth2 $oauth2) {
        $this->oauth2 = $oauth2;
    }

    public function authorizeClient(User $user, Request $request) {
        return $this->oauth2->finishClientAuthorization(true, $user, $request, null);
    }
}