<?php

namespace Parabot\BDN\BotBundle\Entity\Servers\Hooks;

use Doctrine\ORM\Mapping as ORM;

/**
 * CallbackHook
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class CallbackHook {
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
     * @ORM\Column(name="accessor", type="string", length=255)
     */
    private $accessor;

    /**
     * @var string
     *
     * @ORM\Column(name="methodname", type="string", length=255)
     */
    private $methodname;

    /**
     * @var string
     *
     * @ORM\Column(name="desctype", type="string", length=255)
     */
    private $desctype;

    /**
     * @var string
     *
     * @ORM\Column(name="callclass", type="string", length=255)
     */
    private $callclass;

    /**
     * @var string
     *
     * @ORM\Column(name="callmethod", type="string", length=255)
     */
    private $callmethod;

    /**
     * @var string
     *
     * @ORM\Column(name="calldesc", type="string", length=255)
     */
    private $calldesc;

    /**
     * @var string
     *
     * @ORM\Column(name="callargs", type="string", length=255)
     */
    private $callargs;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get accessor
     *
     * @return string
     */
    public function getAccessor() {
        return $this->accessor;
    }

    /**
     * Set accessor
     *
     * @param string $accessor
     *
     * @return CallbackHook
     */
    public function setAccessor($accessor) {
        $this->accessor = $accessor;

        return $this;
    }

    /**
     * Get methodname
     *
     * @return string
     */
    public function getMethodname() {
        return $this->methodname;
    }

    /**
     * Set methodname
     *
     * @param string $methodname
     *
     * @return CallbackHook
     */
    public function setMethodname($methodname) {
        $this->methodname = $methodname;

        return $this;
    }

    /**
     * Get desctype
     *
     * @return string
     */
    public function getDesctype() {
        return $this->desctype;
    }

    /**
     * Set desctype
     *
     * @param string $desctype
     *
     * @return CallbackHook
     */
    public function setDesctype($desctype) {
        $this->desctype = $desctype;

        return $this;
    }

    /**
     * Get callclass
     *
     * @return string
     */
    public function getCallclass() {
        return $this->callclass;
    }

    /**
     * Set callclass
     *
     * @param string $callclass
     *
     * @return CallbackHook
     */
    public function setCallclass($callclass) {
        $this->callclass = $callclass;

        return $this;
    }

    /**
     * Get callmethod
     *
     * @return string
     */
    public function getCallmethod() {
        return $this->callmethod;
    }

    /**
     * Set callmethod
     *
     * @param string $callmethod
     *
     * @return CallbackHook
     */
    public function setCallmethod($callmethod) {
        $this->callmethod = $callmethod;

        return $this;
    }

    /**
     * Get calldesc
     *
     * @return string
     */
    public function getCalldesc() {
        return $this->calldesc;
    }

    /**
     * Set calldesc
     *
     * @param string $calldesc
     *
     * @return CallbackHook
     */
    public function setCalldesc($calldesc) {
        $this->calldesc = $calldesc;

        return $this;
    }

    /**
     * Get callargs
     *
     * @return string
     */
    public function getCallargs() {
        return $this->callargs;
    }

    /**
     * Set callargs
     *
     * @param string $callargs
     *
     * @return CallbackHook
     */
    public function setCallargs($callargs) {
        $this->callargs = $callargs;

        return $this;
    }
}
