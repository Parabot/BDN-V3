<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\UserBundle\Listener;

use Parabot\BDN\UserBundle\Provider\ApiKeyUserProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;

class ApiKeyAuthenticator implements SimplePreAuthenticatorInterface {

    /**
     * @var string
     */
    private $apiKeyCookieKey;

    /**
     * ApiKeyAuthenticator constructor.
     *
     * @param string $apiKeyCookieKey
     */
    public function __construct($apiKeyCookieKey) {
        $this->apiKeyCookieKey = $apiKeyCookieKey;
    }


    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey) {
        if( ! $userProvider instanceof ApiKeyUserProvider) {
            return new JsonResponse(
                [
                    'result' => sprintf(
                        'The user provider must be an instance of ApiKeyUserProvider (%s was given).',
                        get_class($userProvider)
                    ),
                ], 500
            );
        }

        $apiKey   = $token->getCredentials();
        $username = $userProvider->getUsernameForApiKey($apiKey);

        if( ! $username) {
            return new JsonResponse(
                [ 'result' => sprintf('API Key "%s" does not exist.', $apiKey) ], 401
            );
        }

        $user = $userProvider->loadUserByUsername($username);

        return new PreAuthenticatedToken(
            $user, $apiKey, $providerKey, $user->getRoles()
        );
    }

    public function supportsToken(TokenInterface $token, $providerKey) {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }

    public function createToken(Request $request, $providerKey) {
        $apiKey = $request->query->get($this->apiKeyCookieKey);

        if( ! $apiKey) {
            $apiKey = $request->request->get($this->apiKeyCookieKey);
        }

        if( ! $apiKey) {
            $apiKey = $request->cookies->get($this->apiKeyCookieKey);
        }

        if($apiKey === null) {
            throw new AccessDeniedHttpException('No API key found');
        }

        return new PreAuthenticatedToken(
            'anon.', $apiKey, $providerKey
        );
    }
}