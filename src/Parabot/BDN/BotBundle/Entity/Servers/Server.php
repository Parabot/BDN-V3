<?php

namespace Parabot\BDN\BotBundle\Entity\Servers;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToMany;
use Parabot\BDN\UserBundle\Entity\Group;
use Parabot\BDN\UserBundle\Entity\User;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;

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
     * @Groups({"default"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Groups({"default"})
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     * @Groups({"default"})
     */
    private $active = true;

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
     * @Groups({"default"})
     */
    private $authors;

    /**
     * @var ServerDetail[]
     *
     * @ManyToMany(targetEntity="Parabot\BDN\BotBundle\Entity\Servers\ServerDetail")
     * @JoinTable(name="server_details",
     *      joinColumns={@JoinColumn(name="server_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="serverdetail_id", referencedColumnName="id")}
     * )
     * @Groups({"default"})
     */
    private $details;

    /**
     * @var float
     *
     * @ORM\Column(name="version", type="float")
     * @Groups({"default"})
     */
    private $version;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Groups({"default"})
     */
    private $description;

    /**
     * @var Hook[]
     *
     * @OneToMany(targetEntity="Parabot\BDN\BotBundle\Entity\Servers\Hook", mappedBy="server")
     */
    private $hooks;

    /**
     * @var string
     */
    private $path;

    /**
     * @return Hook[]
     */
    public function getHooks() {
        return $this->hooks;
    }

    /**
     * @param Hook[] $hooks
     *
     * @return $this
     */
    public function setHooks($hooks) {
        $this->hooks = $hooks;

        return $this;
    }

    /**
     * @param Hook $hook
     *
     * @return Server
     */
    public function addHook($hook) {
        if(is_array($hook)) {
            $this->addHooks($hook);
        } else {
            $this->hooks[] = $hook;
        }

        return $this;
    }

    /**
     * @param $hooks
     *
     * @return Server
     */
    public function addHooks($hooks) {
        $this->hooks = array_merge($this->hooks, $hooks);

        return $this;
    }

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
     * @return Group[]
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
     * @return ServerDetail[]
     */
    public function getDetails() {
        return $this->details;
    }

    /**
     * @param ServerDetail[] $details
     *
     * @return Server
     */
    public function setDetails($details) {
        $this->details = $details;

        $matches = 0;
        foreach($this->details as $detail) {
            foreach(ServerDetail::DEFAULT_DETAILS as $item) {
                if($detail->getName() == $item) {
                    $matches++;
                }
            }
        }

        if($matches != count(ServerDetail::DEFAULT_DETAILS)) {
            throw new Exception('Amount of details does not match the required amount of details');
        }

        return $this;
    }

    public function getFile() {
        return $this->version . '.jar';
    }

    public function getAbsolutePath() {
        return $this->path . $this->getFile();
    }

    /**
     * @return string
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * @param string $path Directory locating to the app folder
     */
    public function setPath($path) {
        $this->path = $path . '/data/Scripts/' . $this->id . '/';

        if( ! file_exists($this->path)) {
            mkdir($this->path, 0755, true);
        }
    }

    /**
     * @param File $file
     *
     * @throws Exception
     */
    public function insertFile(File $file) {
        if( ! $file->guessExtension() == 'zip') {
            throw new Exception('File extension not allowed, only jar');
        }
        $file->move($this->path, $this->getFile());
    }
}
