<?php

namespace BDN\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Parabot\BDN\BotBundle\Entity\Scripts\Category;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161227160722 extends AbstractMigration implements ContainerAwareInterface {

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

        $categories = [
            'Agility',
            'Combat',
            'Cooking',
            'Crafting',
            'Dungeoneering',
            'Farming',
            'Firemaking',
            'Fishing',
            'Fletching',
            'Herblore',
            'Hunter',
            'Magic',
            'Minigames',
            'Mining',
            'Moneymaking',
            'Other',
            'Prayer',
            'Runecrafting',
            'Slayer',
            'Smithing',
            'Thieving',
            'Utility',
            'Woodcutting',
        ];

        foreach($categories as $category) {
            $c = new Category();
            $c->setName($category);

            $manager->persist($c);
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
