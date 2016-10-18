<?php

namespace Parabot\BDN\BotBundle\Entity\Scripts;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Category {
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
     * @var array
     *
     * @ORM\Column(name="scripts", type="array")
     */
    private $scripts;


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
     * @return Category
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get scripts
     *
     * @return array
     */
    public function getScripts() {
        return $this->scripts;
    }

    /**
     * Set scripts
     *
     * @param array $scripts
     *
     * @return Category
     */
    public function setScripts($scripts) {
        $this->scripts = $scripts;

        return $this;
    }
}
