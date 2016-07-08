<?php
/**
 * @author JKetelaar
 */
namespace Parabot\BDN\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Scheb\TwoFactorBundle\Model\Google\TwoFactorInterface;

/**
 * @ORM\Entity(repositoryClass="Parabot\BDN\UserBundle\Repository\UserRepository")
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
     * @var string
     *
     * @ORM\Column(type="string", name="api_key", length=255, nullable=true)
     */
    private $apiKey = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $googleAuthenticatorSecret;

    /**
     * @var CommunityUser
     *
     * @ORM\OneToOne(targetEntity="CommunityUser", mappedBy="user")
     * @ORM\JoinColumn(name="communityuser_id", referencedColumnName="id")
     */
    private $communityUser;

    /**
     * @ORM\ManyToMany(targetEntity="Parabot\BDN\BotBundle\Entity\Script", mappedBy="scripts")
     */
    private $scripts;

    public function __construct() {
        parent::__construct();
    }

    /**
     * @return string
     */
    public function getApiKey() {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey($apiKey) {
        $this->apiKey = $apiKey;
    }

    /**
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getScripts() {
        return $this->scripts;
    }

    /**
     * @param mixed $scripts
     */
    public function setScripts($scripts) {
        $this->scripts = $scripts;
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

    /**
     * @return CommunityUser
     */
    public function getCommunityUser() {
        return $this->communityUser;
    }

    /**
     * @param CommunityUser $communityUser
     */
    public function setCommunityUser($communityUser) {
        $this->communityUser = $communityUser;
    }
}
