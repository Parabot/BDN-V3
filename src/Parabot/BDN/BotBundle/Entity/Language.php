<?php

namespace Parabot\BDN\BotBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Language
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Language {
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var string
     *
     * @ORM\Column(name="language", type="string", length=32)
     */
    private $language;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive() {
        return $this->active;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Language
     */
    public function setActive($active) {
        $this->active = $active;

        return $this;
    }

    /**
     * Get language
     *
     * @return string
     */
    public function getLanguage() {
        return $this->language;
    }

    /**
     * Set language
     *
     * @param string $language
     *
     * @return Language
     */
    public function setLanguage($language) {
        $this->language = $language;

        return $this;
    }
}
