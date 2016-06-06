<?php

namespace Parabot\BDN\BotBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Script
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Parabot\BDN\BotBundle\Repository\ScriptRepository")
 */
class Script {
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var array
     *
     * @ORM\Column(name="user", type="array")
     */
    private $user;

    /**
     * @var array
     *
     * @ORM\Column(name="users", type="array")
     */
    private $users;

    /**
     * @var array
     *
     * @ORM\Column(name="groups", type="array")
     */
    private $groups;

    /**
     * @var \stdClass
     *
     * @ORM\Column(name="product", type="object")
     */
    private $product;

    /**
     * @var array
     *
     * @ORM\Column(name="categories", type="array")
     */
    private $categories;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="forum", type="integer")
     */
    private $forum;

    /**
     * @var float
     *
     * @ORM\Column(name="version", type="float")
     */
    private $version;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Script
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get user
     *
     * @return array
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param array $user
     *
     * @return Script
     */
    public function setUser($user) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get users
     *
     * @return array
     */
    public function getUsers() {
        return $this->users;
    }

    /**
     * Set users
     *
     * @param array $users
     *
     * @return Script
     */
    public function setUsers($users) {
        $this->users = $users;

        return $this;
    }

    /**
     * Get groups
     *
     * @return array
     */
    public function getGroups() {
        return $this->groups;
    }

    /**
     * Set groups
     *
     * @param array $groups
     *
     * @return Script
     */
    public function setGroups($groups) {
        $this->groups = $groups;

        return $this;
    }

    /**
     * Get product
     *
     * @return \stdClass
     */
    public function getProduct() {
        return $this->product;
    }

    /**
     * Set product
     *
     * @param \stdClass $product
     *
     * @return Script
     */
    public function setProduct($product) {
        $this->product = $product;

        return $this;
    }

    /**
     * Get categories
     *
     * @return array
     */
    public function getCategories() {
        return $this->categories;
    }

    /**
     * Set categories
     *
     * @param array $categories
     *
     * @return Script
     */
    public function setCategories($categories) {
        $this->categories = $categories;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Script
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get forum
     *
     * @return integer
     */
    public function getForum() {
        return $this->forum;
    }

    /**
     * Set forum
     *
     * @param integer $forum
     *
     * @return Script
     */
    public function setForum($forum) {
        $this->forum = $forum;

        return $this;
    }

    /**
     * Get version
     *
     * @return float
     */
    public function getVersion() {
        return $this->version;
    }

    /**
     * Set version
     *
     * @param float $version
     *
     * @return Script
     */
    public function setVersion($version) {
        $this->version = $version;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive() {
        return $this->active;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Script
     */
    public function setActive($active) {
        $this->active = $active;

        return $this;
    }
}
