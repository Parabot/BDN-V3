<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\UserBundle\Entity\Login;

use Doctrine\ORM\Mapping as ORM;
use Parabot\BDN\UserBundle\Entity\User;

/**
 * @ORM\Table("login_access_tokens")
 * @ORM\Entity
 */
class RequestToken {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="t_key", length=255, unique=true)
     */
    private $key;

    /**
     * @var User $user
     *
     * @ORM\ManyToOne(targetEntity="Parabot\BDN\UserBundle\Entity\User")
     */
    private $user = null;

    /**
     * RequestToken constructor.
     */
    public function __construct() { }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getKey() {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey($key) {
        $this->key = $key;
    }

    /**
     * @return User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user = null) {
        $this->user = $user;
    }
}