<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Service\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class ScriptRepositoryService {

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var string[int[]]
     */
    private $groups;

    /**
     * ScriptRepositoryService constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param               $groups
     */
    public function __construct($groups, EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
        $this->groups        = $groups;
    }

    public function getScriptsForUser($user) {
        return $this->entityManager->getRepository('BDNBotBundle:Script')->findScriptsForUser(
            $user,
            $this->groups[ 'script_writers' ]
        );
    }
}