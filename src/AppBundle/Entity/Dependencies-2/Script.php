<?php
/**
 * @author JKetelaar
 */
namespace AppBundle\Entity\Dependencies;

use AppBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Script
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\PBRepository")
 */
class Script extends ParabotDependency {

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="scripts")
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
     * @ORM\Column(name="forum_url", type="string", length=255, nullable=true)
     */
    private $forumUrl;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Scripts\Git", inversedBy="scripts")
     * @ORM\JoinColumn(name="git_id", referencedColumnName="id", nullable=false)
     */
    private $gitId;

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
    public function setActive( $active ) {
        $this->active = $active;

        return $this;
    }

    /**
     * Get userId
     *
     * @return User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Set userId
     *
     * @param User $user
     *
     * @return Script
     *
     */
    public function setUser( $user ) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory() {
        return $this->category;
    }

    /**
     * Set category
     *
     * @param string $category
     *
     * @return Script
     */
    public function setCategory( $category ) {
        $this->category = $category;

        return $this;
    }

    /**
     * Get forumUrl
     *
     * @return string
     */
    public function getForumUrl() {
        return $this->forumUrl;
    }

    /**
     * Set forumUrl
     *
     * @param string $forumUrl
     *
     * @return Script
     */
    public function setForumUrl( $forumUrl ) {
        $this->forumUrl = $forumUrl;

        return $this;
    }

    /**
     * Get gitId
     *
     * @return string
     */
    public function getGitId() {
        return $this->gitId;
    }

    /**
     * Set gitId
     *
     * @param string $gitId
     *
     * @return Script
     */
    public function setGitId( $gitId ) {
        $this->gitId = $gitId;

        return $this;
    }
}

