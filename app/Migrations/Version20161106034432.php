<?php

namespace BDN\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161106034432 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE callback_hook (id INT AUTO_INCREMENT NOT NULL, accessor VARCHAR(255) NOT NULL, methodname VARCHAR(255) NOT NULL, desctype VARCHAR(255) NOT NULL, callclass VARCHAR(255) NOT NULL, callmethod VARCHAR(255) NOT NULL, calldesc VARCHAR(255) NOT NULL, callargs VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE getter_hook (id INT AUTO_INCREMENT NOT NULL, accessor VARCHAR(255) NOT NULL, field VARCHAR(255) NOT NULL, methodname VARCHAR(255) NOT NULL, desctype VARCHAR(255) NOT NULL, descfield VARCHAR(255) NOT NULL, intoclass VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE interface_hook (id INT AUTO_INCREMENT NOT NULL, classname VARCHAR(255) NOT NULL, interface VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invoker_hook (id INT AUTO_INCREMENT NOT NULL, accessor VARCHAR(255) NOT NULL, methodname VARCHAR(255) NOT NULL, invokemethod VARCHAR(255) NOT NULL, desctype VARCHAR(255) NOT NULL, argsdesc VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE setter_hook (id INT AUTO_INCREMENT NOT NULL, accessor VARCHAR(255) NOT NULL, field VARCHAR(255) NOT NULL, methodname VARCHAR(255) NOT NULL, descfield VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE callback_hook');
        $this->addSql('DROP TABLE getter_hook');
        $this->addSql('DROP TABLE interface_hook');
        $this->addSql('DROP TABLE invoker_hook');
        $this->addSql('DROP TABLE setter_hook');
    }
}
