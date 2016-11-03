<?php

namespace Parabot\BDN\BotBundle\Entity\Servers;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Parabot\BDN\UserBundle\Entity\User;

/**
 * ServerUse
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class ServerUse {
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var User
     *
     * @ManyToMany(targetEntity="Parabot\BDN\UserBundle\Entity\User")
     * @JoinTable(name="user_serveruses",
     *      joinColumns={@JoinColumn(name="serveruse_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     */
    private $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datetime", type="datetime")
     */
    private $datetime;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return ServerUse
     */
    public function setUser($user) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get datetime
     *
     * @return \DateTime
     */
    public function getDatetime() {
        return $this->datetime;
    }

    /**
     * Set datetime
     *
     * @param \DateTime $datetime
     *
     * @return ServerUse
     */
    public function setDatetime($datetime) {
        $this->datetime = $datetime;

        return $this;
    }
}
