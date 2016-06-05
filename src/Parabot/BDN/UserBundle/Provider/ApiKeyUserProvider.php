<?php
/**
 * @author JKetelaar
 */
namespace Parabot\BDN\UserBundle\Provider;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class ApiKeyUserProvider implements UserProviderInterface {
    public function getUsernameForApiKey($apiKey) {
        $username = '';

        return $username;
    }

    public function loadUserByUsername($username) {
        return new User(
            $username, null, [ 'ROLE_USER' ]
        );
    }

    public function refreshUser(UserInterface $user) {
        throw new UnsupportedUserException();
    }

    public function supportsClass($class) {
        return 'Symfony\Component\Security\Core\User\User' === $class;
    }
}