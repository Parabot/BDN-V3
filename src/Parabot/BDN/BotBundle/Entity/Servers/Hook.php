<?php

namespace Parabot\BDN\BotBundle\Entity\Servers;

use Doctrine\ORM\Mapping as ORM;
use Parabot\BDN\BotBundle\Entity\Servers\Hooks\CallbackHook;
use Parabot\BDN\BotBundle\Entity\Servers\Hooks\GetterHook;
use Parabot\BDN\BotBundle\Entity\Servers\Hooks\InterfaceHook;
use Parabot\BDN\BotBundle\Entity\Servers\Hooks\InvokerHook;
use Parabot\BDN\BotBundle\Entity\Servers\Hooks\SetterHook;

/**
 * Hook
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Parabot\BDN\BotBundle\Repository\HookRepository")
 */
class Hook implements CallbackHook, GetterHook, InterfaceHook, InvokerHook, SetterHook {

    const INTERFACE_TYPE = 'interfaces';
    const GETTER_TYPE    = 'getters';
    const SETTER_TYPE    = 'setters';
    const INVOKER_TYPE   = 'invokers';
    const CALLBACK_TYPE  = 'callbacks';

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
     * @ORM\Column(name="accessor", type="string", length=255, nullable=true)
     */
    private $accessor;

    /**
     * @var string
     *
     * @ORM\Column(name="methodname", type="string", length=255, nullable=true)
     */
    private $methodname;

    /**
     * @var string
     *
     * @ORM\Column(name="desctype", type="string", length=255, nullable=true)
     */
    private $desctype;

    /**
     * @var string
     *
     * @ORM\Column(name="callclass", type="string", length=255, nullable=true)
     */
    private $callclass;

    /**
     * @var string
     *
     * @ORM\Column(name="callmethod", type="string", length=255, nullable=true)
     */
    private $callmethod;

    /**
     * @var string
     *
     * @ORM\Column(name="calldesc", type="string", length=255, nullable=true)
     */
    private $calldesc;

    /**
     * @var string
     *
     * @ORM\Column(name="callargs", type="string", length=255, nullable=true)
     */
    private $callargs;

    /**
     * @var string
     *
     * @ORM\Column(name="field", type="string", length=255, nullable=true)
     */
    private $field;

    /**
     * @var string
     *
     * @ORM\Column(name="descfield", type="string", length=255, nullable=true)
     */
    private $descfield;

    /**
     * @var string
     *
     * @ORM\Column(name="intoclass", type="string", length=255, nullable=true)
     */
    private $intoclass;

    /**
     * @var string
     *
     * @ORM\Column(name="classname", type="string", length=255, nullable=true)
     */
    private $classname;

    /**
     * @var string
     *
     * @ORM\Column(name="interface", type="string", length=255, nullable=true)
     */
    private $interface;

    /**
     * @var string
     *
     * @ORM\Column(name="invokemethod", type="string", length=255, nullable=true)
     */
    private $invokemethod;

    /**
     * @var string
     *
     * @ORM\Column(name="argsdesc", type="string", length=255, nullable=true)
     */
    private $argsdesc;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return Hook
     */
    public function setType($type) {
        $this->type = $type;

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
     * @return Hook
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
     * @return Hook
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
     * @return Hook
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
     * @return Hook
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
     * @return Hook
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
     * @return Hook
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
     * @return Hook
     */
    public function setCallargs($callargs) {
        $this->callargs = $callargs;

        return $this;
    }

    /**
     * Get field
     *
     * @return string
     */
    public function getField() {
        return $this->field;
    }

    /**
     * Set field
     *
     * @param string $field
     *
     * @return Hook
     */
    public function setField($field) {
        $this->field = $field;

        return $this;
    }

    /**
     * Get descfield
     *
     * @return string
     */
    public function getDescfield() {
        return $this->descfield;
    }

    /**
     * Set descfield
     *
     * @param string $descfield
     *
     * @return Hook
     */
    public function setDescfield($descfield) {
        $this->descfield = $descfield;

        return $this;
    }

    /**
     * Get intoclass
     *
     * @return string
     */
    public function getIntoclass() {
        return $this->intoclass;
    }

    /**
     * Set intoclass
     *
     * @param string $intoclass
     *
     * @return Hook
     */
    public function setIntoclass($intoclass) {
        $this->intoclass = $intoclass;

        return $this;
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
     * @return Hook
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
     * @return Hook
     */
    public function setInterface($interface) {
        $this->interface = $interface;

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
     * @return Hook
     */
    public function setInvokemethod($invokemethod) {
        $this->invokemethod = $invokemethod;

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
     * @return Hook
     */
    public function setArgsdesc($argsdesc) {
        $this->argsdesc = $argsdesc;

        return $this;
    }

    /**
     * @return array
     */
    public function toCallbackArray() {
        $array = [];

        if($this->accessor != null) {
            $array[ 'accessor' ] = $this->accessor;
        }

        if($this->methodname != null) {
            $array[ 'methodname' ] = $this->methodname;
        }

        if($this->desctype != null) {
            $array[ 'desc' ] = $this->desctype;
        }

        if($this->callclass != null) {
            $array[ 'callclass' ] = $this->callclass;
        }

        if($this->callmethod != null) {
            $array[ 'callmethod' ] = $this->callmethod;
        }

        if($this->calldesc != null) {
            $array[ 'calldesc' ] = $this->calldesc;
        }

        if($this->callargs != null) {
            $array[ 'callargs' ] = $this->callargs;
        }

        return $array;
    }

    /**
     * @return array
     */
    public function toGetterArray() {
        $array = [];

        if($this->accessor != null) {
            $array[ 'accessor' ] = $this->accessor;
        }

        if($this->field != null) {
            $array[ 'field' ] = $this->field;
        }

        if($this->methodname != null) {
            $array[ 'methodname' ] = $this->methodname;
        }

        if($this->desctype != null) {
            $array[ 'desc' ] = $this->desctype;
        }

        if($this->descfield != null) {
            $array[ 'descfield' ] = $this->descfield;
        }

        if($this->intoclass != null) {
            $array[ 'into' ] = $this->intoclass;
        }

        return $array;
    }

    /**
     * @return array
     */
    public function toInterfaceArray() {
        $array = [];

        if($this->classname != null) {
            $array[ 'classname' ] = $this->classname;
        }

        if($this->interface != null) {
            $array[ 'interface' ] = $this->interface;
        }

        return $array;
    }

    /**
     * @return array
     */
    public function toInvokerArray() {
        // TODO: Implement toInvokerArray() method.
    }

    /**
     * @return array
     */
    public function toSetterArray() {
        // TODO: Implement toSetterArray() method.
    }
}
