<?php
namespace Parabot\BDN\UserBundle\Entity\OAuth;

use Doctrine\ORM\Mapping as ORM;
use FOS\OAuthServerBundle\Entity\Client as BaseClient;

/**
 * Client
 *
 * @ORM\Table("oauth2_clients")
 * @ORM\Entity(repositoryClass="Parabot\BDN\UserBundle\Repository\ClientRepository")
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

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="name", length=255)
     */
    private $name;

    public function __construct() {
        parent::__construct();
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }
}

