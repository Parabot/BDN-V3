<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\OAuthServerBundle\Service;

use Doctrine\ORM\EntityManager;
use FOS\OAuthServerBundle\Entity\ClientManager;
use Parabot\BDN\UserBundle\Entity\OAuth\Client;
use Parabot\BDN\UserBundle\Repository\ClientRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class ClientCreator {
    const ARGUMENTS = [
        'name',
        'redirect-uri',
        'grant-type',
    ];

    /**
     * @var ClientManager
     */
    private $clientManager;

    /**
     * @var ClientRepository
     */
    private $clientRepository;

    /**
     * ClientCreator constructor.
     *
     * @param ClientManager $clientManager
     * @param EntityManager $entityManager
     */
    public function __construct(ClientManager $clientManager, EntityManager $entityManager) {
        $this->clientManager    = $clientManager;
        $this->clientRepository = $entityManager->getRepository('BDNUserBundle:OAuth\Client');
    }


    /**
     * @param $values
     *
     * @return JsonResponse
     */
    public function createClient($values) {
        if(($name = $values[ 'name' ]) != null) {
            if(($redirectUris = $values[ 'redirect-uri' ]) != null) {
                if( ! is_array($redirectUris)) {
                    if(($exploded = explode(',', $redirectUris)) && count($exploded) >= 2) {
                        $redirectUris = $exploded;
                    } else {
                        $redirectUris = [ $redirectUris ];
                    }
                }

                foreach($redirectUris as $redirectUri) {
                    if(filter_var($redirectUri, FILTER_VALIDATE_URL) === false) {
                        return new JsonResponse(
                            [ 'result' => 'Given URL \'' . $redirectUri . '\' is not valid; format should be \'http://example.com/path\'' ],
                            400
                        );
                    }
                }

                if($this->clientRepository->redirectUrisAvailable($redirectUris) == false) {
                    return new JsonResponse([ 'result' => 'Client already exists with one of your redirects' ], 400);
                }

                /**
                 * @var Client $client
                 */
                $client = $this->clientManager->createClient();
                $client->setRedirectUris($redirectUris);
                if($values[ 'grant-type' ] != null) {
                    if( ! is_array($values[ 'grant-type' ])) {
                        $values[ 'grant-type' ] = [ $values[ 'grant-type' ] ];
                    }
                    $client->setAllowedGrantTypes($values[ 'grant-type' ]);
                } else {
                    $client->setAllowedGrantTypes([ 'token', 'authorization_code', 'refresh_token' ]);
                }
                $client->setName($name);

                $this->clientManager->updateClient($client);

                return new JsonResponse(
                    [
                        'client_id' => $client->getPublicId(),
                        'secret_id' => $client->getSecret(),
                    ]
                );
            } else {
                return new JsonResponse([ 'result' => 'Incorrect parameter \'redirect-uri\' given' ], 400);
            }
        } else {
            return new JsonResponse([ 'result' => 'Incorrect parameter \'name\' given' ], 400);
        }
    }
}