<?php

namespace Parabot\BDN\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Session
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Parabot\BDN\UserBundle\Repository\SessionRepository")
 */
class Session {
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
     * @ORM\Column(name="ip", type="string", length=45)
     */
    private $ip;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp() {
        return $this->ip;
    }

    /**
     * Set ip
     *
     * @param string $ip
     *
     * @return Session
     */
    public function setIp($ip) {
        $this->ip = $ip;

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
     * @return Session
     */
    public function setDate($date) {
        $this->date = $date;

        return $this;
    }
}
