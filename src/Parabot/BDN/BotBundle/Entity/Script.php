<?php

namespace Parabot\BDN\BotBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Parabot\BDN\BotBundle\Entity\Scripts\Git;
use Parabot\BDN\BotBundle\Entity\Scripts\Release;
use Parabot\BDN\BotBundle\Entity\Scripts\Review;
use Parabot\BDN\UserBundle\Entity\Group;
use Parabot\BDN\UserBundle\Entity\User;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Script
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Parabot\BDN\BotBundle\Repository\ScriptRepository")
 */
class Script {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Groups({"default", "review"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     *
     * @Groups({"default"})
     */
    private $name;

    /**
     * @var User[]
     *
     * @ORM\ManyToMany(targetEntity="Parabot\BDN\UserBundle\Entity\User")
     * @ORM\JoinTable(name="script_authors",
     *      joinColumns={@ORM\JoinColumn(name="script_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     *
     * @Groups({"default"})
     */
    private $authors;

    /**
     * @var User[]
     *
     * @ORM\ManyToMany(targetEntity="Parabot\BDN\UserBundle\Entity\User")
     * @ORM\JoinTable(name="script_users",
     *      joinColumns={@ORM\JoinColumn(name="script_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     *
     * @Groups({"script_users"})
     */
    private $users;

    /**
     * @var Group[]
     *
     * @ORM\ManyToMany(targetEntity="Parabot\BDN\UserBundle\Entity\Group")
     * @ORM\JoinTable(name="script_groups",
     *      joinColumns={@ORM\JoinColumn(name="script_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     *
     * @Groups({"default"})
     */
    private $groups;

    /**
     * @var \stdClass
     *
     * @ORM\Column(name="product", type="object")
     */
    private $product;

    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="Parabot\BDN\BotBundle\Entity\Scripts\Category")
     * @ORM\JoinTable(name="script_categories",
     *      joinColumns={@ORM\JoinColumn(name="script_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")}
     * )
     *
     * @Groups({"default"})
     */
    private $categories;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     *
     * @Groups({"default"})
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="forum", type="integer", nullable=true)
     *
     * @Groups({"default"})
     */
    private $forum;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     *
     * @Groups({"default"})
     */
    private $active = true;

    /**
     * @var Git
     *
     * @ORM\OneToOne(targetEntity="Parabot\BDN\BotBundle\Entity\Scripts\Git")
     *
     * @Groups({"developer"})
     */
    private $git;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Parabot\BDN\UserBundle\Entity\User", inversedBy="createdScripts")
     * @ORM\JoinColumn(name="creator_id", referencedColumnName="id")
     *
     * @Groups({"default"})
     */
    private $creator;

    /**
     * @var string
     *
     * @ORM\Column(name="build_type_id", type="string", length=255, nullable=true)
     *
     * @Groups({"developer"})
     */
    private $buildTypeId;

    /**
     * @var Review[]
     *
     * @ORM\OneToMany(targetEntity="Parabot\BDN\BotBundle\Entity\Scripts\Review", mappedBy="script")
     */
    private $reviews;

    /**
     * @var string
     */
    private $path;

    /**
     * @var Release[]
     *
     * @ORM\OneToMany(targetEntity="Parabot\BDN\BotBundle\Entity\Scripts\Release", mappedBy="script")
     *
     * @Groups({"default"})
     */
    private $releases;

    /**
     * @return Release[]
     */
    public function getReleases() {
        return $this->releases;
    }

    /**
     * @param Release[] $releases
     *
     * @return Script
     */
    public function setReleases($releases) {
        $this->releases = $releases;

        return $this;
    }

    /**
     * @Groups({"default"})
     *
     * @param bool $accepted
     *
     * @return float
     */
    public function getAverageReviewStars($accepted = true) {
        if(count($this->getReviews($accepted)) <= 0) {
            return 0;
        }

        $totalStars   = 0;
        $totalReviews = 0;
        foreach($this->getReviews($accepted) as $review) {
            $totalStars += $review->getStars();
            $totalReviews++;
        }

        return round($totalStars / $totalReviews, 1);
    }

    /**
     * @Groups({"default"})
     *
     * @param bool $accepted
     *
     * @return Review[]
     */
    public function getReviews($accepted = true) {
        if($accepted !== false) {
            $current         = new \DateTime();
            $acceptedReviews = [];
            foreach($this->reviews as $review) {
                if($review->isAccepted() || $review->getDate()->diff($current)->days >= 7) {
                    $acceptedReviews[] = $review;
                }
            }

            return $acceptedReviews;
        }

        return $this->reviews;
    }

    /**
     * @param Review[] $reviews
     *
     * @return Script
     */
    public function setReviews($reviews) {
        $this->reviews = $reviews;

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
     * @return Script
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get user
     *
     * @return User[]
     */
    public function getAuthors() {
        return $this->authors;
    }

    /**
     * Set user
     *
     * @param User[] $authors
     *
     * @return Script
     */
    public function setAuthors($authors) {
        $this->authors = $authors;

        return $this;
    }

    /**
     * Get users
     *
     * @return array
     */
    public function getUsers() {
        return $this->users;
    }

    /**
     * Set users
     *
     * @param array $users
     *
     * @return Script
     */
    public function setUsers($users) {
        $this->users = $users;

        return $this;
    }

    public function hasUser(User $user) {
        foreach($this->users as $u) {
            if($u->getId() === $user->getId()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get groups
     *
     * @return array
     */
    public function getGroups() {
        return $this->groups;
    }

    /**
     * Set groups
     *
     * @param array $groups
     *
     * @return Script
     */
    public function setGroups($groups) {
        $this->groups = $groups;

        return $this;
    }

    /**
     * Get product
     *
     * @return \stdClass
     */
    public function getProduct() {
        return $this->product;
    }

    /**
     * Set product
     *
     * @param \stdClass $product
     *
     * @return Script
     */
    public function setProduct($product) {
        $this->product = $product;

        return $this;
    }

    /**
     * Get categories
     *
     * @return array
     */
    public function getCategories() {
        return $this->categories;
    }

    /**
     * Set categories
     *
     * @param array $categories
     *
     * @return Script
     */
    public function setCategories($categories) {
        $this->categories = $categories;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Script
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get forum
     *
     * @return integer
     */
    public function getForum() {
        return $this->forum;
    }

    /**
     * Set forum
     *
     * @param integer $forum
     *
     * @return Script
     */
    public function setForum($forum) {
        $this->forum = $forum;

        return $this;
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
     * @return Script
     */
    public function setActive($active) {
        $this->active = $active;

        return $this;
    }

    /**
     * @return Git
     */
    public function getGit() {
        return $this->git;
    }

    /**
     * @param Git $git
     *
     * @return Script
     */
    public function setGit($git) {
        $this->git = $git;

        return $this;
    }

    /**
     * @return User
     */
    public function getCreator() {
        return $this->creator;
    }

    /**
     * @param User $creator
     */
    public function setCreator($creator) {
        $this->creator = $creator;
    }

    /**
     * @return string
     */
    public function getBuildTypeId() {
        return $this->buildTypeId;
    }

    /**
     * @param string $buildTypeId
     */
    public function setBuildTypeId($buildTypeId) {
        $this->buildTypeId = $buildTypeId;
    }

    public function hasAuthor(User $author) {
        foreach($this->authors as $a) {
            if($a->getId() == $author->getId()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * @param string $path Directory locating to the app folder
     */
    public function setPath($path) {
        $this->path = $path . '/data/Scripts/' . $this->id . '/';

        if( ! file_exists($this->path)) {
            mkdir($this->path, 0755, true);
        }
    }

    /**
     * @param Release $release
     */
    public function addRelease(Release $release) {
        $this->releases[] = $release;
    }
}
