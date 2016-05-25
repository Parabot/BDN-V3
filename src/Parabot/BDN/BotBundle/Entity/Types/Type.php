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
     */
    private $type;

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
     */
    private $path;

    /**
     * Type constructor.
     *
     * @param string $type
     */
    public function __construct($type) {
        $this->releaseDate = new \DateTime();
        $this->type        = $type;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
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
    public function getPath() {
        return $this->path;
    }

    /**
     * @param string $path Directory locating to the app folder
     */
    public function setPath($path) {
        $this->path = $path . '/data/' . $this->type . '/';

        if( ! file_exists($this->path)) {
            mkdir($this->path, 0755, true);
        }
    }

}