<?php
/**
 * @author JKetelaar
 */
namespace Parabot\BDN\StoreBundle\Entity;

use Sylius\Component\Order\Model\Order as SyliusOrder;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table("orders")
 * @ORM\Entity
 */
class Order extends SyliusOrder{

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false, unique=true)
     */
    private $email;

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }
}