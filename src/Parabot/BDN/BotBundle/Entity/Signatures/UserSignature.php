<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Entity\Signatures;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserSignature
 *
 * @ORM\Entity()
 */
class UserSignature {

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
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var AbstractSignature
     *
     * @ORM\ManyToOne(targetEntity="Parabot\BDN\BotBundle\Entity\Signatures\AbstractSignature", inversedBy="userSignatures")
     */
    private $abstractSignature;
}