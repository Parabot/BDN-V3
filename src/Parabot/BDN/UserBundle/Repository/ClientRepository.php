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
         * @var $client         Client
         */
        $clientRepository = $this->getEntityManager()->getRepository('BDNUserBundle:OAuth\\Client');

        foreach($clientRepository->findAll() as $clientInstance) {
            if(count(array_intersect($clientInstance->getRedirectUris(), $redirectUris)) > 0) {
                return false;
            }
        }

        return true;
    }
}