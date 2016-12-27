<?php

namespace Parabot\BDN\BotBundle\Entity\Scripts;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Git
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Git {
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
     * @ORM\Column(name="url", type="string", length=255)
     *
     * @Groups({"developer"})
     */
    private $url;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Git
     */
    public function setUrl($url) {
        $this->url = $url;

        return $this;
    }
}
