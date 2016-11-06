<?php

namespace BDN\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161106231750 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE hook (id INT AUTO_INCREMENT NOT NULL, accessor VARCHAR(255) DEFAULT NULL, methodname VARCHAR(255) DEFAULT NULL, desctype VARCHAR(255) DEFAULT NULL, callclass VARCHAR(255) DEFAULT NULL, callmethod VARCHAR(255) DEFAULT NULL, calldesc VARCHAR(255) DEFAULT NULL, callargs VARCHAR(255) DEFAULT NULL, field VARCHAR(255) DEFAULT NULL, descfield VARCHAR(255) DEFAULT NULL, intoclass VARCHAR(255) DEFAULT NULL, classname VARCHAR(255) DEFAULT NULL, interface VARCHAR(255) DEFAULT NULL, invokemethod VARCHAR(255) DEFAULT NULL, argsdesc VARCHAR(255) DEFAULT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE callback_hook');
        $this->addSql('DROP TABLE getter_hook');
        $this->addSql('DROP TABLE interface_hook');
        $this->addSql('DROP TABLE invoker_hook');
        $this->addSql('DROP TABLE setter_hook');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE callback_hook (id INT AUTO_INCREMENT NOT NULL, accessor VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, methodname VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, desctype VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, callclass VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, callmethod VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, calldesc VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, callargs VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE getter_hook (id INT AUTO_INCREMENT NOT NULL, accessor VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, field VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, methodname VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, desctype VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, descfield VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, intoclass VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE interface_hook (id INT AUTO_INCREMENT NOT NULL, classname VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, interface VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invoker_hook (id INT AUTO_INCREMENT NOT NULL, accessor VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, methodname VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, invokemethod VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, desctype VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, argsdesc VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE setter_hook (id INT AUTO_INCREMENT NOT NULL, accessor VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, field VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, methodname VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, descfield VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE hook');
    }
}
