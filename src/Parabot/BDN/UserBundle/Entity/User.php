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
     * @var int
     *
     * @ORM\Column(type="integer", name="forums_id")
     */
    private $forumsId;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", name="forums_access_token")
     */
    private $forumsAccessToken;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", name="community_id")
     */
    private $communityId;

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
    public function setGoogleAuthenticatorSecret($googleAuthenticatorSecret) {
        $this->googleAuthenticatorSecret = $googleAuthenticatorSecret;
    }

    /**
     * @return int
     */
    public function getForumsId() {
        return $this->forumsId;
    }

    /**
     * @param int $forumsId
     */
    public function setForumsId($forumsId) {
        $this->forumsId = $forumsId;
    }

    /**
     * @return int
     */
    public function getCommunityId() {
        return $this->communityId;
    }

    /**
     * @param int $communityId
     */
    public function setCommunityId($communityId) {
        $this->communityId = $communityId;
    }

    /**
     * @return int
     */
    public function getForumsAccessToken() {
        return $this->forumsAccessToken;
    }

    /**
     * @param int $forumsAccessToken
     */
    public function setForumsAccessToken($forumsAccessToken) {
        $this->forumsAccessToken = $forumsAccessToken;
    }

    /**
     * @param int $communityId
     *
     * @return bool
     */
    public function hasGroupId($communityId) {
        /**
         * @var Group $group
         */
        foreach($this->getGroups() as $group) {
            if($group->getCommunityId() == $communityId) {
                return true;
            }
        }

        return false;
    }
}
