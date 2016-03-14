<?php
namespace Parabot\BDN\UserBundle\Entity\OAuth;

use Doctrine\ORM\Mapping as ORM;
use FOS\OAuthServerBundle\Entity\Client as BaseClient;
use Parabot\BDN\UserBundle\Entity\OAuth;

/**
 * Client
 *
 * @ORM\Table("oauth2_clients")
 * @ORM\Entity(repositoryClass="Parabot\BDN\UserBundle\Repository")
 */
class Client extends BaseClient {

    /**
     * @var $id integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct() {
        parent::__construct();
    }
}

