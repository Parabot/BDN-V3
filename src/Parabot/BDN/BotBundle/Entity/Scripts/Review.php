<?php

namespace Parabot\BDN\BotBundle\Entity\Scripts;

use Doctrine\ORM\Mapping as ORM;
use Parabot\BDN\BotBundle\Entity\Script;
use Parabot\BDN\UserBundle\Entity\User;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Review
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Parabot\BDN\BotBundle\Repository\ReviewRepository")
 */
class Review {
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Groups({"review", "default"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="review", type="text")
     *
     * @Groups({"review"})
     */
    private $review;

    /**
     * @var integer
     *
     * @ORM\Column(name="stars", type="integer")
     *
     * @Groups({"review", "default"})
     */
    private $stars;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     *
     * @Groups({"review"})
     */
    private $date;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Parabot\BDN\UserBundle\Entity\User", inversedBy="review")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *
     * @Groups({"review"})
     */
    private $user;

    /**
     * @var Script
     *
     * @ORM\ManyToOne(targetEntity="Parabot\BDN\BotBundle\Entity\Script", inversedBy="review")
     * @ORM\JoinColumn(name="script_id", referencedColumnName="id")
     *
     * @Groups({"review"})
     */
    private $script;

    /**
     * @var boolean
     *
     * @ORM\Column(name="accepted", type="boolean")
     *
     * @Groups({"review"})
     */
    private $accepted;

    /**
     * Review constructor.
     */
    public function __construct() {
        $this->date     = new \DateTime();
        $this->accepted = false;
    }

    /**
     * @return bool
     */
    public function isAccepted() {
        return $this->accepted;
    }

    /**
     * @param bool $accepted
     *
     * @return Review
     */
    public function setAccepted($accepted) {
        $this->accepted = $accepted;

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
     * Get review
     *
     * @return string
     */
    public function getReview() {
        return $this->review;
    }

    /**
     * Set review
     *
     * @param string $review
     *
     * @return Review
     */
    public function setReview($review) {
        $this->review = $review;

        return $this;
    }

    /**
     * Get stars
     *
     * @return integer
     */
    public function getStars() {
        return $this->stars;
    }

    /**
     * Set stars
     *
     * @param integer $stars
     *
     * @return Review
     */
    public function setStars($stars) {
        if($stars > 10 || $stars < 1) {
            throw new \Exception('Stars may not be less than 1 or more than 10.');
        }
        $this->stars = round($stars);

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
     * @return Review
     */
    public function setDate($date) {
        $this->date = $date;

        return $this;
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
     * @return Review
     */
    public function setUser($user) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get script
     *
     * @return Script
     */
    public function getScript() {
        return $this->script;
    }

    /**
     * Set script
     *
     * @param Script $script
     *
     * @return Review
     */
    public function setScript($script) {
        $this->script = $script;

        return $this;
    }
}
