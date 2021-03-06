<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Service;

use Doctrine\ORM\EntityManager;
use Parabot\BDN\BotBundle\Entity\Types\Type;

class TypeHelper
{

    /**
     * @var string[] $types
     */
    private static $types = [
        'client' => 'BDNBotBundle:Types\Client',
        'randoms' => 'BDNBotBundle:Types\Randoms',
        'default-provider' => 'BDNBotBundle:Types\Providers\DefaultProvider',
        'os-scape-provider' => 'BDNBotBundle:Types\Providers\OSScapeProvider',
        'pkhonor-provider' => 'BDNBotBundle:Types\Providers\PkHonorProvider',
        'dreamscape-provider' => 'BDNBotBundle:Types\Providers\DreamScapeProvider',
        'ikov-provider' => 'BDNBotBundle:Types\Providers\IkovProvider',
        'locopk-provider' => 'BDNBotBundle:Types\Providers\LocoPKProvider',
        'roatzpk-provider' => 'BDNBotBundle:Types\Providers\RoatzPKProvider',
        'runewild-provider' => 'BDNBotBundle:Types\Providers\RuneWildProvider',
        'soulplay-provider' => 'BDNBotBundle:Types\Providers\SoulPlayProvider',
    ];

    private $entityManager;

    /**
     * TypeHelper constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function typeExists($type)
    {
        foreach (self::$types as $key => $value) {
            if (strtolower($key) == strtolower($type)) {
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
    public function createType($type)
    {
        $repository = $this->getRepositorySlug($type);
        $class = $this->entityManager->getClassMetadata($repository)->getName();

        return new $class();
    }

    /**
     * @param string $type
     *
     * @return string
     */
    public function getRepositorySlug($type)
    {
        return self::$types[strtolower($type)];
    }

    /**
     * @param string $server
     * @return string
     */
    public function serverToType($server)
    {
        switch ($server) {
            case 'OS-Scape':
                $type = 'os-scape-provider';
                break;
            case 'Ikov':
                $type = 'ikov-provider';
                break;
            case 'Dreamscape':
                $type = 'dreamscape-provider';
                break;
            case 'PkHonor':
                $type = 'pkhonor-provider';
                break;
            case 'LocoPK':
                $type = 'locopk-provider';
                break;
            case 'RoatzPK':
                $type = 'roatzpk-provider';
                break;
            case 'RuneWild':
                $type = 'runewild-provider';
                break;
            case 'SoulPlay':
                $type = 'soulplay-provider';
                break;
            default:
                $type = 'default-provider';
                break;
        }

        return $type;
    }
}
