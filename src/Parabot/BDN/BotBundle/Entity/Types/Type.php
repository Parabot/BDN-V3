<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Entity\Types;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\MappedSuperclass
 */
abstract class Type {

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
     * @ORM\Column(name="version", type="string", length=255)
     */
    private $version;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="release_date", type="datetime")
     */
    private $releaseDate;

    /**
     * @var string
     *
     * @ORM\Column(name="branch", type="string", length=255)
     */
    private $branch;

    /**
     * @ORM\Column(name="stable", type="boolean")
     *
     * @var bool
     */
    private $stable;

    /**
     * @var int
     *
     * @ORM\Column(name="build_id", type="integer")
     */
    private $build;
    
    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean", options={"default" = 1})
     */
    private $active = true;

    /**
     * @var string
     */
    private $path;

    /**
     * Type constructor.
     */
    public function __construct() {
        $this->releaseDate = new \DateTime();
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
     * Get string
     *
     * @return string
     */
    public function getVersion() {
        return $this->version;
    }

    /**
     * Set string
     *
     * @param string $string
     *
     * @return Client
     */
    public function setVersion($string) {
        $this->version = $string;

        return $this;
    }

    /**
     * Get releaseDate
     *
     * @return \DateTime
     */
    public function getReleaseDate() {
        return $this->releaseDate;
    }

    /**
     * Set releaseDate
     *
     * @param \DateTime $releaseDate
     *
     * @return Client
     */
    public function setReleaseDate($releaseDate) {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getBranch() {
        return $this->branch;
    }

    /**
     * @param string $branch
     */
    public function setBranch($branch) {
        $this->branch = $branch;
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
        $this->path = $path . '/data/' . $this->getType() . '/';

        if( ! file_exists($this->path)) {
            mkdir($this->path, 0755, true);
        }
    }

    /**
     * Get stability
     *
     * @return boolean
     */
    public function getStable() {
        return $this->stable;
    }

    /**
     * @param boolean $stable
     */
    public function setStable($stable) {
        $this->stable = $stable;
    }

    /**
     * @return int
     */
    public function getBuild() {
        return $this->build;
    }

    /**
     * @param int $build
     */
    public function setBuild($build) {
        $this->build = $build;
    }

    /**
     * @return boolean
     */
    public function isActive() {
        return $this->active;
    }

    /**
     * @param boolean $active
     */
    public function setActive($active) {
        $this->active = $active;
    }

    /**
     * @return string
     */
    public abstract function getType();

    /**
     * @return string
     */
    public abstract function getTravisRepository();
    
    /**
     * @return string
     */
    public abstract function getName();

    public function getFile() {
        return $this->path . $this->version . '.jar';
    }

}