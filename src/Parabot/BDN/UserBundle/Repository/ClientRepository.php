<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Parabot\BDN\UserBundle\Entity\OAuth\Client;

class ClientRepository extends EntityRepository {

    /**
     * @param string[] $redirectUris
     *
     * @return bool
     */
    public function redirectUrisAvailable($redirectUris) {
        /**
         * @var $clientInstance Client
         */
        $clientRepository = $this->getEntityManager()->getRepository('BDNUserBundle:OAuth\\Client');

        foreach($clientRepository->findAll() as $clientInstance) {
            if(count(array_intersect($clientInstance->getRedirectUris(), $redirectUris)) > 0) {
                return false;
            }
        }

        return true;
    }

    public function isValidRedirectUri($uri) {
        /**
         * @var $clientInstance Client
         */
        $clientRepository = $this->getEntityManager()->getRepository('BDNUserBundle:OAuth\\Client');
        $uri              = parse_url($uri);

        foreach($clientRepository->findAll() as $clientInstance) {
            foreach($clientInstance->getRedirectUris() as $clientUri) {
                $clientUri = parse_url($clientUri);
                if($uri[ 'host' ] == $clientUri[ 'host' ]) {
                    return true;
                }
            }
        }

        return false;
    }
}