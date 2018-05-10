<?php

namespace Parabot\BDN\BotBundle\Entity\Scripts;

use Doctrine\ORM\Mapping as ORM;
use Parabot\BDN\BotBundle\Entity\Script;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Release
 *
 * @ORM\Table(name="releases")
 * @ORM\Entity(repositoryClass="Parabot\BDN\BotBundle\Repository\ReleaseRepository")
 */
class Release {
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Groups({"default"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="changelog", type="text", nullable=true)
     *
     * @Groups({"default"})
     */
    private $changelog;

    /**
     * @var float
     *
     * @ORM\Column(name="version", type="float")
     *
     * @Groups({"default"})
     */
    private $version;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     *
     * @Groups({"default"})
     */
    private $date;

    /**
     * @var Script
     *
     * @ORM\ManyToOne(targetEntity="Parabot\BDN\BotBundle\Entity\Script", inversedBy="releases")
     * @ORM\JoinColumn(name="script_id", referencedColumnName="id")
     */
    private $script;

    function __construct() {
        $this->date = new \DateTime();
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
     * @return Script
     */
    public function getScript() {
        return $this->script;
    }

    /**
     * @param Script $script
     *
     * @return Release
     */
    public function setScript($script) {
        $this->script = $script;

        return $this;
    }

    /**
     * Get changelog
     *
     * @return string
     */
    public function getChangelog() {
        return $this->changelog;
    }

    /**
     * Set changelog
     *
     * @param string $changelog
     *
     * @return Release
     */
    public function setChangelog($changelog) {
        $this->changelog = $changelog;

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
     * @return Release
     */
    public function setVersion($version) {
        $this->version = $version;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Release
     */
    public function setDate($date) {
        $this->date = $date;

        return $this;
    }
}
