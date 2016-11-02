<?php

namespace Parabot\BDN\UserBundle\Entity\Users;

use Doctrine\ORM\Mapping as ORM;
use Parabot\BDN\UserBundle\Entity\User;

/**
 * SlackInvite
 *
 * @ORM\Entity(repositoryClass="Parabot\BDN\UserBundle\Repository\Users\SlackInviteRepository")
 * @ORM\Table()
 */
class SlackInvite {
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var User $user
     *
     * @ORM\ManyToOne(targetEntity="Parabot\BDN\UserBundle\Entity\User")
     */
    private $user;

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
     * @return SlackInvite
     */
    public function setUser($user) {
        $this->user = $user;

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
     * @return SlackInvite
     */
    public function setDate($date) {
        $this->date = $date;

        return $this;
    }
}
