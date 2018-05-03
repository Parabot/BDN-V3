<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Parabot\BDN\BotBundle\Entity\Types\Type;

class TypeHelper {

    /**
     * @var string[] $types
     */
    private static $types = [
        'client'              => 'BDNBotBundle:Types\Client',
        'randoms'             => 'BDNBotBundle:Types\Randoms',
        'default-provider'    => 'BDNBotBundle:Types\Providers\DefaultProvider',
        'os-scape-provider'   => 'BDNBotBundle:Types\Providers\OSScapeProvider',
        'pkhonor-provider'    => 'BDNBotBundle:Types\Providers\PkHonorProvider',
        'dreamscape-provider' => 'BDNBotBundle:Types\Providers\DreamScapeProvider',
        'locopk-provider'     => 'BDNBotBundle:Types\Providers\LocoPKProvider',
        'soulplay-provider'     => 'BDNBotBundle:Types\Providers\SoulPlayProvider',
    ];

    private $entityManager;

    /**
     * TypeHelper constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function typeExists($type) {
        foreach(self::$types as $key => $value) {
            if(strtolower($key) == strtolower($type)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $type
     *
     * @return Type
     */
    public function createType($type) {
        $repository = $this->getRepositorySlug($type);
        $class      = $this->entityManager->getClassMetadata($repository)->getName();

        return new $class();
    }

    /**
     * @param string $type
     *
     * @return string
     */
    public function getRepositorySlug($type) {
        return self::$types[ strtolower($type) ];
    }
}