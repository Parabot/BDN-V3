<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Entity\Signatures;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\MappedSuperclass
 */
abstract class AbstractSignature {

    const TYPES = [ 'image' ];

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
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="file", type="string", length=255)
     */
    private $file;

    /**
     * @var UserSignature[]
     *
     * @ORM\OneToMany(targetEntity="Parabot\BDN\BotBundle\Entity\Signatures\UserSignature", mappedBy="abstractSignature")
     */
    private $userSignatures;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $path;

    /**
     * AbstractSignature constructor.
     *
     * @param string $type
     */
    public function __construct($type = 'image') {
        $this->setType($type);
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type) {
        $type = strtolower($type);
        if(in_array($type, self::TYPES)) {
            $this->type = $type;
        } else {
            throw new Exception('Unknown type given (' . $type . ')');
        }
    }

    /**
     * @return string
     */
    public function getFile() {
        return $this->file;
    }

    /**
     * @param string $file
     *
     * @return AbstractSignature
     */
    public function setFile($file) {
        $this->file = $file;

        return $this;
    }

    /**
     * @return UserSignature[]
     *
     */
    public function getUserSignatures() {
        return $this->userSignatures;
    }

    /**
     * @param UserSignature[] $userSignatures
     *
     * @return AbstractSignature
     */
    public function setUserSignatures($userSignatures) {
        $this->userSignatures = $userSignatures;

        return $this;
    }

    public final function getAbsoluteFilePath() {
        return $this->path . $this->file;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return AbstractSignature
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * @param File   $file
     * @param string $name
     */
    public function insertFile(File $file, $name) {
        if( ! in_array($file->guessExtension(), $this->getAllowedExtensions())) {
            throw new Exception('File extension not allowed, only ' . implode(', ', $this->getAllowedExtensions()));
        }
        $file->move($this->path, $name);
        $this->file = $name;

        if( ! $this->isAllowedFile()) {
            throw new Exception('File type not allowed for upload');
        }
    }

    /**
     * @return string[]
     */
    public abstract function getAllowedExtensions();

    /**
     * @return bool
     */
    public abstract function isAllowedFile();

    /**
     * @param string $path Directory locating to the app folder
     */
    public final function setPath($path) {
        $this->path = $path . '/data/signatures/';

        if( ! file_exists($this->path)) {
            mkdir($this->path, 0755, true);
        }
    }
}