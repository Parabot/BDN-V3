<?php
/**
 * @author JKetelaar
 */
namespace Parabot\BDN\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Scheb\TwoFactorBundle\Model\Google\TwoFactorInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends BaseUser implements TwoFactorInterface {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="Parabot\BDN\UserBundle\Entity\Group")
     * @ORM\JoinTable(name="user_user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $googleAuthenticatorSecret;

    public function __construct() {
        parent::__construct();
    }

    /**
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Return the Google Authenticator secret
     * When an empty string or null is returned, the Google authentication is disabled.
     *
     * @return string|null
     */
    public function getGoogleAuthenticatorSecret() {
        return $this->googleAuthenticatorSecret;
    }

    /**
     * Set the Google Authenticator secret
     *
     * @param integer $googleAuthenticatorSecret
     */
    public function setGoogleAuthenticatorSecret( $googleAuthenticatorSecret ) {
        $this->googleAuthenticatorSecret = $googleAuthenticatorSecret;
    }
}
