<?php

namespace Parabot\BDN\BotBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Parabot\BDN\BotBundle\Entity\Scripts\Git;

/**
 * ScriptRepository
 */
class ScriptRepository extends EntityRepository {

    /**
     * @param int $id
     *
     * @return null|Git
     */
    public function findGitById($id) {
        /** @var Script $result */
        $result = $this->findOneBy([ 'id' => $id ]);
        if($result != null) {
            if(($git = $result->getGit()) != null) {
                return $result->getGit();
            }
        }

        return null;
    }
}
