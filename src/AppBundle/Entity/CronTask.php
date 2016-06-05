<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class CronTask {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * 
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var array
     * 
     * @ORM\Column(type="array")
     */
    private $commands;

    /**
     * @var int
     * 
     * @ORM\Column(name="`interval`", type="integer")
     */
    private $interval;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastrun;

    /**
     * CronTask constructor.
     */
    public function __construct() { }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getCommands() {
        return $this->commands;
    }

    /**
     * @param array $commands
     */
    public function setCommands($commands) {
        $this->commands = $commands;
    }

    /**
     * @return int
     */
    public function getInterval() {
        return $this->interval;
    }

    /**
     * @param int $interval
     */
    public function setInterval($interval) {
        $this->interval = $interval;
    }

    /**
     * @return \DateTime
     */
    public function getLastrun() {
        return $this->lastrun;
    }

    /**
     * @param \DateTime $lastrun
     */
    public function setLastrun($lastrun) {
        $this->lastrun = $lastrun;
    }
}