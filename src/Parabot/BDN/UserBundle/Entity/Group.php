<?php
/**
 * @author JKetelaar
 */
namespace Parabot\BDN\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\Group as BaseGroup;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="Parabot\BDN\UserBundle\Repository\Users\GroupRepository")
 * @ORM\Table(name="user_group")
 */
class Group extends BaseGroup {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
     *
     * @Groups({"default"})
     */
    protected $id;

    /**
     * @ORM\Column(name="community_id", type="integer")
     *
     * @var int
     *
     * @Groups({"default"})
     */
    private $communityId;

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getCommunityId() {
        return $this->communityId;
    }

    /**
     * @param int $communityId
     */
    public function setCommunityId($communityId) {
        $this->communityId = $communityId;
    }

    /**
     * @return string
     *
     * @Groups({"default"})
     */
    public function getName() {
        return parent::getName();
    }
}
