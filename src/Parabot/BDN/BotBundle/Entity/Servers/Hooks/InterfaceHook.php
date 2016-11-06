<?php

namespace Parabot\BDN\BotBundle\Entity\Servers\Hooks;

use Doctrine\ORM\Mapping as ORM;

/**
 * Interface
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class InterfaceHook {
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
     * @ORM\Column(name="classname", type="string", length=255)
     */
    private $classname;

    /**
     * @var string
     *
     * @ORM\Column(name="interface", type="string", length=255)
     */
    private $interface;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get classname
     *
     * @return string
     */
    public function getClassname() {
        return $this->classname;
    }

    /**
     * Set classname
     *
     * @param string $classname
     *
     * @return InterfaceHook
     */
    public function setClassname($classname) {
        $this->classname = $classname;

        return $this;
    }

    /**
     * Get interface
     *
     * @return string
     */
    public function getInterface() {
        return $this->interface;
    }

    /**
     * Set interface
     *
     * @param string $interface
     *
     * @return InterfaceHook
     */
    public function setInterface($interface) {
        $this->interface = $interface;

        return $this;
    }
}
