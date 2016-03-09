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
class Client extends ParabotDependency {

    /**
     * @var string
     *
     * @ORM\Column(name="commit", type="string", length=50, unique=true)
     */
    private $commit;

    public function getCommit() {
        return $this->commit;
    }

    public function setCommit( $commit ) {
        $this->commit = $commit;
    }
}