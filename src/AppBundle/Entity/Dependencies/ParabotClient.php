<?php
/**
 * @author JKetelaar
 */
namespace AppBundle\Entity\Dependencies;

use Doctrine\ORM\Mapping as ORM;

/**
 * Client
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\PBRepository")
 */
class ParabotClient extends ParabotDependency {

    /**
     * @var string
     *
     * @ORM\Column(name="commit", type="string", length=50, unique=true)
     */
    private $commit;

    /**
     * @var \DateTime $release_date
     *
     * @ORM\Column(name="release_date", type="date", nullable=true)
     */
    private $release_date;

    public function getReleaseDate() {
        return $this->release_date;
    }

    public function setReleaseDate($release_date) {
        $this->release_date = $release_date;
    }

    public function getCommit() {
        return $this->commit;
    }

    public function setCommit($commit) {
        $this->commit = $commit;
    }
}