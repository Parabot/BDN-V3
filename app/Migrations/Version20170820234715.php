<?php

namespace BDN\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Parabot\BDN\UserBundle\Entity\Group;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170820234715 extends AbstractMigration implements ContainerAwareInterface {

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema) {
        $doctrine = $this->container->get('doctrine');
        $manager  = $doctrine->getManager();

        $groups = [
            4  => [
                'name'  => 'Administrators',
                'roles' => [
                    'ROLE_ADMIN',
                ],
            ],
            23 => [
                'name'  => 'Banned',
                'roles' => [
                    'ROLE_USER',
                ],
            ],
            21 => [
                'name'  => 'Coding Legend',
                'roles' => [
                    'ROLE_USER',
                ],
            ],
            12 => [
                'name'  => 'Developer',
                'roles' => [
                    'ROLE_DEVELOPER',
                ],
            ],
            7  => [
                'name'  => 'Donator',
                'roles' => [
                    'ROLE_USER',
                ],
            ],
            3  => [
                'name'  => 'Members',
                'roles' => [
                    'ROLE_USER',
                ],
            ],
            6  => [
                'name'  => 'Moderator',
                'roles' => [
                    'ROLE_USER',
                ],
            ],
            20 => [
                'name'  => 'Premium script',
                'roles' => [
                    'ROLE_USER',
                ],
            ],
            9  => [
                'name'  => 'Script writer',
                'roles' => [
                    'ROLE_USER',
                ],
            ],
            19 => [
                'name'  => 'Section mod',
                'roles' => [
                    'ROLE_USER',
                ],
            ],
            22 => [
                'name'  => 'Server developer',
                'roles' => [
                    'ROLE_USER',
                ],
            ],
            8  => [
                'name'  => 'Sponsor',
                'roles' => [
                    'ROLE_USER',
                ],
            ],
            17 => [
                'name'  => 'Supporter',
                'roles' => [
                    'ROLE_USER',
                ],
            ],
        ];

        foreach($groups as $id => $group) {
            $group = new Group($group[ 'name' ], $group[ 'roles' ]);
            $group->setCommunityId($id);

            $manager->persist($group);
        }

        $manager->flush();
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema) {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
