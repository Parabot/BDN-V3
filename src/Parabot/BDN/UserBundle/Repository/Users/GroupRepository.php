<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\UserBundle\Repository\Users;

use Doctrine\ORM\EntityRepository;
use Parabot\BDN\UserBundle\Entity\Group;

class GroupRepository extends EntityRepository {
    public function findAllNotBanned() {
        $groups = [];
        /**
         * @var Group $group
         */
        foreach($this->findAll() as $group) {
            if( ! in_array($group->getCommunityId(), [ 23 ])) {
                $groups[] = $group;
            }
        }

        return $groups;
    }
}