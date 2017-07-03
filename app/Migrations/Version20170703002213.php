<?php

namespace BDN\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170703002213 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE server_slack_channel (id INT AUTO_INCREMENT NOT NULL, channel VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_provider (id INT AUTO_INCREMENT NOT NULL, version VARCHAR(255) NOT NULL, release_date DATETIME NOT NULL, branch VARCHAR(255) NOT NULL, stable TINYINT(1) NOT NULL, build_id INT NOT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE type_default_provider');
        $this->addSql('DROP TABLE type_osscape_provider');
        $this->addSql('DROP INDEX IDX_5A6DD5F6A53A8AA ON server');
        $this->addSql('ALTER TABLE server CHANGE provider_id server_id INT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5A6DD5F61844E6B7 ON server (server_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE type_default_provider (id INT AUTO_INCREMENT NOT NULL, version VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, release_date DATETIME NOT NULL, branch VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, stable TINYINT(1) NOT NULL, build_id INT NOT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_osscape_provider (id INT AUTO_INCREMENT NOT NULL, version VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, release_date DATETIME NOT NULL, branch VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, stable TINYINT(1) NOT NULL, build_id INT NOT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE server_slack_channel');
        $this->addSql('DROP TABLE type_provider');
        $this->addSql('DROP INDEX UNIQ_5A6DD5F61844E6B7 ON server');
        $this->addSql('ALTER TABLE server CHANGE server_id provider_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_5A6DD5F6A53A8AA ON server (provider_id)');
    }
}
