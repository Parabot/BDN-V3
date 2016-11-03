<?php

namespace Parabot\BDN\BotBundle\Entity\Servers;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Parabot\BDN\UserBundle\Entity\Group;
use Parabot\BDN\UserBundle\Entity\User;

/**
 * Server
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Parabot\BDN\BotBundle\Repository\ServerRepository")
 */
class Server {
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
     * @var Group[]
     *
     * @ORM\ManyToMany(targetEntity="Parabot\BDN\UserBundle\Entity\Group")
     * @ORM\JoinTable(name="server_groups",
     *      joinColumns={@ORM\JoinColumn(name="server_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    private $groups;

    /**
     * @var User[]
     *
     * @ORM\ManyToMany(targetEntity="Parabot\BDN\UserBundle\Entity\User")
     * @ORM\JoinTable(name="server_authors",
     *      joinColumns={@ORM\JoinColumn(name="server_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     */
    private $authors;

    /**
     * @var ServerUse[]
     *
     * @ManyToMany(targetEntity="Parabot\BDN\BotBundle\Entity\Servers\ServerUse")
     * @JoinTable(name="server_uses",
     *      joinColumns={@JoinColumn(name="server_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="serveruse_id", referencedColumnName="id")}
     * )
     */
    private $uses;

    /**
     * @var float
     *
     * @ORM\Column(name="version", type="float")
     */
    private $version;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;


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
     * @return Server
     */
    public function setName($name) {
        $this->name = $name;

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
     * @return Server
     */
    public function setActive($active) {
        $this->active = $active;

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
     * @return Server
     */
    public function setGroups($groups) {
        $this->groups = $groups;

        return $this;
    }

    /**
     * Get author
     *
     * @return User[]
     */
    public function getAuthors() {
        return $this->authors;
    }

    /**
     * Set author
     *
     * @param User[] $authors
     *
     * @return Server
     */
    public function setAuthors($authors) {
        $this->authors = $authors;

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
     * @return Server
     */
    public function setVersion($version) {
        $this->version = $version;

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
     * @return Server
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * @return ServerUse[]
     */
    public function getUses() {
        return $this->uses;
    }

    /**
     * @param ServerUse[] $uses
     *
     * @return Server
     */
    public function setUses($uses) {
        $this->uses = $uses;

        return $this;
    }
}
