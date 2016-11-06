<?php

namespace Parabot\BDN\BotBundle\Entity\Servers\Hooks;

use Doctrine\ORM\Mapping as ORM;

/**
 * InvokerHook
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class InvokerHook {
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
     * @ORM\Column(name="invokemethod", type="string", length=255)
     */
    private $invokemethod;

    /**
     * @var string
     *
     * @ORM\Column(name="desctype", type="string", length=255)
     */
    private $desctype;

    /**
     * @var string
     *
     * @ORM\Column(name="argsdesc", type="string", length=255)
     */
    private $argsdesc;


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
     * @return InvokerHook
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
     * @return InvokerHook
     */
    public function setMethodname($methodname) {
        $this->methodname = $methodname;

        return $this;
    }

    /**
     * Get invokemethod
     *
     * @return string
     */
    public function getInvokemethod() {
        return $this->invokemethod;
    }

    /**
     * Set invokemethod
     *
     * @param string $invokemethod
     *
     * @return InvokerHook
     */
    public function setInvokemethod($invokemethod) {
        $this->invokemethod = $invokemethod;

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
     * @return InvokerHook
     */
    public function setDesctype($desctype) {
        $this->desctype = $desctype;

        return $this;
    }

    /**
     * Get argsdesc
     *
     * @return string
     */
    public function getArgsdesc() {
        return $this->argsdesc;
    }

    /**
     * Set argsdesc
     *
     * @param string $argsdesc
     *
     * @return InvokerHook
     */
    public function setArgsdesc($argsdesc) {
        $this->argsdesc = $argsdesc;

        return $this;
    }
}
