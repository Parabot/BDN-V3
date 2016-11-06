<?php

namespace Parabot\BDN\BotBundle\Entity\Servers\Hooks;

use Doctrine\ORM\Mapping as ORM;

/**
 * GetterHook
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class GetterHook {
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
     * @ORM\Column(name="field", type="string", length=255)
     */
    private $field;

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
     * @ORM\Column(name="descfield", type="string", length=255)
     */
    private $descfield;

    /**
     * @var string
     *
     * @ORM\Column(name="intoclass", type="string", length=255)
     */
    private $intoclass;


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
     * @return GetterHook
     */
    public function setAccessor($accessor) {
        $this->accessor = $accessor;

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
     * @return GetterHook
     */
    public function setField($field) {
        $this->field = $field;

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
     * @return GetterHook
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
     * @return GetterHook
     */
    public function setDesctype($desctype) {
        $this->desctype = $desctype;

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
     * @return GetterHook
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
     * @return GetterHook
     */
    public function setIntoclass($intoclass) {
        $this->intoclass = $intoclass;

        return $this;
    }
}
