<?php

namespace BDN\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170421133243 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE server DROP FOREIGN KEY FK_5A6DD5F61844E6B7');
        $this->addSql('CREATE TABLE type_default_provider (id INT AUTO_INCREMENT NOT NULL, version VARCHAR(255) NOT NULL, release_date DATETIME NOT NULL, branch VARCHAR(255) NOT NULL, stable TINYINT(1) NOT NULL, build_id INT NOT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_osscape_provider (id INT AUTO_INCREMENT NOT NULL, version VARCHAR(255) NOT NULL, release_date DATETIME NOT NULL, branch VARCHAR(255) NOT NULL, stable TINYINT(1) NOT NULL, build_id INT NOT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE build');
        $this->addSql('DROP TABLE community_user');
        $this->addSql('DROP TABLE parabot_client');
        $this->addSql('DROP TABLE type_provider');
        $this->addSql('DROP INDEX IDX_5A6DD5F61844E6B7 ON server');
        $this->addSql('ALTER TABLE server CHANGE server_id provider_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_5A6DD5F6A53A8AA ON server (provider_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE build (id INT AUTO_INCREMENT NOT NULL, git_id INT NOT NULL, build_date DATETIME NOT NULL, status LONGTEXT NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:simple_array)\', result LONGTEXT NOT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE community_user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, member_group_id INT NOT NULL, mgroup_others VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, members_pass_hash VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, members_pass_salt VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, member_id INT NOT NULL, UNIQUE INDEX UNIQ_4CC23C837597D3FE (member_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE parabot_client (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, description VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, version DOUBLE PRECISION NOT NULL, commit VARCHAR(50) NOT NULL COLLATE utf8_unicode_ci, path VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, release_date DATE DEFAULT NULL, UNIQUE INDEX UNIQ_6C53A6304ED42EAD (commit), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_provider (id INT AUTO_INCREMENT NOT NULL, version VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, release_date DATETIME NOT NULL, branch VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, stable TINYINT(1) NOT NULL, build_id INT NOT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE type_default_provider');
        $this->addSql('DROP TABLE type_osscape_provider');
        $this->addSql('DROP INDEX IDX_5A6DD5F6A53A8AA ON server');
        $this->addSql('ALTER TABLE server CHANGE provider_id server_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE server ADD CONSTRAINT FK_5A6DD5F61844E6B7 FOREIGN KEY (server_id) REFERENCES type_provider (id)');
        $this->addSql('CREATE INDEX IDX_5A6DD5F61844E6B7 ON server (server_id)');
    }
}
