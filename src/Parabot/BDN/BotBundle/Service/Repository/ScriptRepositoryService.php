<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Service\Repository;

use Doctrine\ORM\EntityManager;
use Parabot\BDN\BotBundle\Repository\ScriptRepository;

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
     * @param EntityManager $entityManager
     * @param               $groups
     */
    public function __construct(EntityManager $entityManager, $groups) {
        $this->entityManager = $entityManager;
        $this->groups = $groups;
    }

    public function getScriptsForUser($user){
        return $this->entityManager->getRepository('BDNBotBundle:Script')->findScriptsForUser($user, $this->groups['script_writers']);
    }
}