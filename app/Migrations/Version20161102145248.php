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
class Version20161102145248 extends AbstractMigration implements ContainerAwareInterface {
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

        $repository = $manager->getRepository('BDNUserBundle:Group');

        /**
         * @var Group $banned
         */
        $banned = $repository->findOneBy([ 'name' => 'Banned' ]);
        if($banned != null) {
            $banned->setRoles([ 'ROLE_BANNED' ]);
        }

        $manager->persist($banned);
        $manager->flush();

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema) {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
