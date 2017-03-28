<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Parabot\BDN\BotBundle\Entity\Script;
use Parabot\BDN\BotBundle\Entity\Scripts\Review;
use Parabot\BDN\UserBundle\Entity\OAuth\Client;
use Scheb\TwoFactorBundle\Model\Google\TwoFactorInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="Parabot\BDN\UserBundle\Repository\UserRepository")
 * @ORM\Table(name="user")
 */
class User extends BaseUser implements TwoFactorInterface {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Groups({"administrators"})
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="Parabot\BDN\UserBundle\Entity\Group")
     * @ORM\JoinTable(name="user_user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     *
     * @Groups({"administrators", "owner"})
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
     *
     * @Groups({"administrators"})
     */
    private $communityId;

    /**
     * @var Script[]
     *
     * @ORM\ManyToMany(targetEntity="Parabot\BDN\BotBundle\Entity\Script", mappedBy="scripts")
     */
    private $scripts;

    /**
     * @var Script[]
     *
     * @ORM\OneToMany(targetEntity="Parabot\BDN\BotBundle\Entity\Script", mappedBy="creator")
     */
    private $createdScripts;

    /**
     * @var Client[]
     *
     * @ORM\ManyToMany(targetEntity="Parabot\BDN\UserBundle\Entity\OAuth\Client")
     * @ORM\JoinTable(name="user_oauth_clients",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="client_id", referencedColumnName="id")}
     * )
     */
    private $clientAccesses;

    /**
     * @var Review[]
     *
     * @ORM\OneToMany(targetEntity="Parabot\BDN\BotBundle\Entity\Scripts\Review", mappedBy="user")
     */
    private $reviews;

    public function __construct() {
        parent::__construct();
    }

    /**
     * @return Review[]
     */
    public function getReviews() {
        return $this->reviews;
    }

    /**
     * @param Review[] $reviews
     *
     * @return User
     */
    public function setReviews($reviews) {
        $this->reviews = $reviews;

        return $this;
    }

    /**
     * @return Client[]
     */
    public function getClientAccesses() {
        return $this->clientAccesses;
    }

    /**
     * @param Client[] $clientAccesses
     */
    public function setClientAccesses($clientAccesses) {
        $this->clientAccesses = $clientAccesses;
    }

    public function addClientAccesses(Client $clientAccess) {
        foreach($this->clientAccesses as $client) {
            if($client->getId() === $clientAccess->getId()) {
                return;
            }
        }
        $this->clientAccesses[] = $clientAccess;
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
     * @param int  $id
     *
     * @param bool $community
     *
     * @return bool
     */
    public function hasGroupId($id, $community = false) {
        /**
         * @var Group $group
         */
        foreach($this->getGroups() as $group) {
            if(($community === true ? $group->getCommunityId() : $group->getId()) == $id) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string
     *
     * @Groups({"default", "review"})
     */
    public function getUsername() {
        return parent::getUsername();
    }

    /**
     * @return \DateTime
     *
     * @Groups({"administrators"})
     */
    public function getLastLogin() {
        return parent::getLastLogin();
    }

    /**
     * @return string
     *
     * @Groups({"administrators"})
     */
    public function getEmail() {
        return parent::getEmail();
    }

    /**
     * @return mixed
     */
    public function getCreatedScripts() {
        return $this->createdScripts;
    }

    /**
     * @param mixed $createdScripts
     */
    public function setCreatedScripts($createdScripts) {
        $this->createdScripts = $createdScripts;
    }
}
