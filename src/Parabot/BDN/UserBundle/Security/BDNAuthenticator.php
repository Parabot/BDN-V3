<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\UserBundle\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimpleFormAuthenticatorInterface;

class BDNAuthenticator implements SimpleFormAuthenticatorInterface {

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey) {
        try {
            $user = $userProvider->loadUserByUsername($token->getUsername());
        } catch(UsernameNotFoundException $e) {
            return new JsonResponse([ 'result' => 'Invalid username or password' ], 401);
        }

        $passwordValid = $this->encoder->isPasswordValid($user, $token->getCredentials());

        if($passwordValid) {
            return new UsernamePasswordToken(
                $user, $user->getPassword(), $providerKey, $user->getRoles()
            );
        }

        return new JsonResponse([ 'result' => 'Invalid username or password' ], 401);
    }

    public function supportsToken(TokenInterface $token, $providerKey) {
        return $token instanceof UsernamePasswordToken && $token->getProviderKey() === $providerKey;
    }

    public function createToken(Request $request, $username, $password, $providerKey) {
        return new UsernamePasswordToken($username, $password, $providerKey);
    }
}