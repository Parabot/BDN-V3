<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Parabot\BDN\UserBundle\Entity\Group;

class LoadUserGroupData implements FixtureInterface {
    public function load(ObjectManager $manager) {

        $groups = [
            4 => [
                'name' => 'Administrators',
                'roles' => [
                    'ROLE_ADMIN'
                ]
            ],
            23 => [
                'name' => 'Banned',
                'roles' => [
                    'ROLE_USER'
                ]
            ],
            21 => [
                'name' => 'Coding Legend',
                'roles' => [
                    'ROLE_USER'
                ]
            ],
            12 => [
                'name' => 'Developer',
                'roles' => [
                    'ROLE_DEVELOPER'
                ]
            ],
            7 => [
                'name' => 'Donator',
                'roles' => [
                    'ROLE_USER'
                ]
            ],
            3 => [
                'name' => 'Members',
                'roles' => [
                    'ROLE_USER'
                ]
            ],
            6 => [
                'name' => 'Moderator',
                'roles' => [
                    'ROLE_USER'
                ]
            ],
            20 => [
                'name' => 'Premium script',
                'roles' => [
                    'ROLE_USER'
                ]
            ],
            9 => [
                'name' => 'Script writer',
                'roles' => [
                    'ROLE_USER'
                ]
            ],
            19 => [
                'name' => 'Section mod',
                'roles' => [
                    'ROLE_USER'
                ]
            ],
            22 => [
                'name' => 'Server developer',
                'roles' => [
                    'ROLE_USER'
                ]
            ],
            8 => [
                'name' => 'Sponsor',
                'roles' => [
                    'ROLE_USER'
                ]
            ],
            17 => [
                'name' => 'Supporter',
                'roles' => [
                    'ROLE_USER'
                ]
            ],
        ];
        
        foreach($groups as $id => $group){
            $group = new Group($group['name'], $group['roles']);
            $group->setCommunityId($id);
            
            $manager->persist($group);
        }

        $manager->flush();
    }
}