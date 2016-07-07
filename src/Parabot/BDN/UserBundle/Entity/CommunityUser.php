<?php

namespace Parabot\BDN\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommunityUser
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Parabot\BDN\UserBundle\Repository\CommunityUserRepository")
 */
class CommunityUser {
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
     * @var integer
     *
     * @ORM\Column(name="member_group_id", type="integer")
     */
    private $memberGroupId;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="mgroup_others", type="string", length=255, nullable=true)
     */
    private $mgroupOthers;

    /**
     * @var string
     *
     * @ORM\Column(name="members_pass_hash", type="string", length=255)
     */
    private $membersPassHash;

    /**
     * @var string
     *
     * @ORM\Column(name="members_pass_salt", type="string", length=255)
     */
    private $membersPassSalt;

    /**
     * @var int
     * 
     * @ORM\Column(name="member_id", type="integer", unique=true)
     */
    private $member_id;

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
     * @return CommunityUser
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get memberGroupId
     *
     * @return integer
     */
    public function getMemberGroupId() {
        return $this->memberGroupId;
    }

    /**
     * Set memberGroupId
     *
     * @param integer $memberGroupId
     *
     * @return CommunityUser
     */
    public function setMemberGroupId($memberGroupId) {
        $this->memberGroupId = $memberGroupId;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return CommunityUser
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get mgroupOthers
     *
     * @return string
     */
    public function getMgroupOthers() {
        return $this->mgroupOthers;
    }

    /**
     * Set mgroupOthers
     *
     * @param string $mgroupOthers
     *
     * @return CommunityUser
     */
    public function setMgroupOthers($mgroupOthers) {
        $this->mgroupOthers = $mgroupOthers;

        return $this;
    }

    /**
     * Get membersPassHash
     *
     * @return string
     */
    public function getMembersPassHash() {
        return $this->membersPassHash;
    }

    /**
     * Set membersPassHash
     *
     * @param string $membersPassHash
     *
     * @return CommunityUser
     */
    public function setMembersPassHash($membersPassHash) {
        $this->membersPassHash = $membersPassHash;

        return $this;
    }

    /**
     * Get membersPassSalt
     *
     * @return string
     */
    public function getMembersPassSalt() {
        return $this->membersPassSalt;
    }

    /**
     * Set membersPassSalt
     *
     * @param string $membersPassSalt
     *
     * @return CommunityUser
     */
    public function setMembersPassSalt($membersPassSalt) {
        $this->membersPassSalt = $membersPassSalt;

        return $this;
    }

    /**
     * @return int
     */
    public function getMemberId() {
        return $this->member_id;
    }

    /**
     * @param int $member_id
     */
    public function setMemberId($member_id) {
        $this->member_id = $member_id;
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
     * @return CommunityUser
     */
    public function setUser($user) {
        $this->user = $user;

        return $this;
    }
}
