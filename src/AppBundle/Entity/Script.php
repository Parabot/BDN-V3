<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Script
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ScriptRepository")
 */
class Script
{
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
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="scripts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="category", type="string", length=50)
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="forum_url", type="string", length=255, nullable=true)
     */
    private $forumUrl;

    /**
     * @var float
     *
     * @ORM\Column(name="version", type="float")
     */
    private $version;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Scripts\Git", inversedBy="scripts")
     * @ORM\JoinColumn(name="git_id", referencedColumnName="id", nullable=false)
     */
    private $gitId;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Script
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Script
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set userId
     *
     * @param User $user
     * @return Script
     *
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get userId
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set category
     *
     * @param string $category
     *
     * @return Script
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Script
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set forumUrl
     *
     * @param string $forumUrl
     *
     * @return Script
     */
    public function setForumUrl($forumUrl)
    {
        $this->forumUrl = $forumUrl;

        return $this;
    }

    /**
     * Get forumUrl
     *
     * @return string
     */
    public function getForumUrl()
    {
        return $this->forumUrl;
    }

    /**
     * Set version
     *
     * @param float $version
     *
     * @return Script
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version
     *
     * @return float
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set gitId
     *
     * @param string $gitId
     *
     * @return Script
     */
    public function setGitId($gitId)
    {
        $this->gitId = $gitId;

        return $this;
    }

    /**
     * Get gitId
     *
     * @return string
     */
    public function getGitId()
    {
        return $this->gitId;
    }
}

