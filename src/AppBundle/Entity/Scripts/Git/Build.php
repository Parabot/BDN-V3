<?php
namespace AppBundle\Entity\Scripts\Git;

use Doctrine\ORM\Mapping as ORM;

/**
 * Build
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Build {
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="git_id", type="integer")
     */
    private $gitId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="build_date", type="datetime")
     */
    private $buildDate;

    /**
     * @var array
     *
     * @ORM\Column(name="status", type="simple_array")
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="result", type="text")
     */
    private $result;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get gitId
     *
     * @return integer
     */
    public function getGitId() {
        return $this->gitId;
    }

    /**
     * Set gitId
     *
     * @param integer $gitId
     *
     * @return Build
     */
    public function setGitId( $gitId ) {
        $this->gitId = $gitId;

        return $this;
    }

    /**
     * Get buildDate
     *
     * @return \DateTime
     */
    public function getBuildDate() {
        return $this->buildDate;
    }

    /**
     * Set buildDate
     *
     * @param \DateTime $buildDate
     *
     * @return Build
     */
    public function setBuildDate( $buildDate ) {
        $this->buildDate = $buildDate;

        return $this;
    }

    /**
     * Get status
     *
     * @return array
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Set status
     *
     * @param array $status
     *
     * @return Build
     */
    public function setStatus( $status ) {
        $this->status = $status;

        return $this;
    }

    /**
     * Get result
     *
     * @return string
     */
    public function getResult() {
        return $this->result;
    }

    /**
     * Set result
     *
     * @param string $result
     *
     * @return Build
     */
    public function setResult( $result ) {
        $this->result = $result;

        return $this;
    }
}
