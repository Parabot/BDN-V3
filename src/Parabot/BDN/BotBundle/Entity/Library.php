<?php

namespace Parabot\BDN\BotBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Library
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Library {
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
     * @var float
     *
     * @ORM\Column(name="version", type="float")
     */
    private $version;

    /**
     * @var string
     */
    private $path;


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
     * @return Library
     */
    public function setName($name) {
        $this->name = $name;

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
     * @return Library
     */
    public function setVersion($version) {
        $this->version = $version;

        return $this;
    }

    public function getAbsolutePath() {
        return $this->path . $this->getFile();
    }

    public function getFile(){
        return $this->version . '.jar';
    }

    public function getPath() {
        return $this->path;
    }

    /**
     * @param File   $file
     *
     * @throws Exception
     */
    public function insertFile(File $file) {
        if( ! $file->guessExtension() == 'zip') {
            throw new Exception('File extension not allowed, only jar');
        }
        $file->move($this->path, $this->getFile());
    }

    /**
     * @param string $path Directory locating to the app folder
     */
    public function setPath($path) {
        $this->path = $path . '/data/Libraries/' . $this->id . '/';

        if( ! file_exists($this->path)) {
            mkdir($this->path, 0755, true);
        }
    }
}
