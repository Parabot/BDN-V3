<?php

namespace BDN\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170120214013 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE releases (id INT AUTO_INCREMENT NOT NULL, script_id INT DEFAULT NULL, changelog LONGTEXT NOT NULL, version DOUBLE PRECISION NOT NULL, date DATETIME NOT NULL, INDEX IDX_7896E4D1A1C01850 (script_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE releases ADD CONSTRAINT FK_7896E4D1A1C01850 FOREIGN KEY (script_id) REFERENCES script (id)');
        $this->addSql('DROP TABLE `release`');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE `release` (id INT AUTO_INCREMENT NOT NULL, script_id INT DEFAULT NULL, changelog LONGTEXT NOT NULL COLLATE utf8_unicode_ci, version DOUBLE PRECISION NOT NULL, date DATETIME NOT NULL, INDEX IDX_9E47031DA1C01850 (script_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `release` ADD CONSTRAINT FK_9E47031DA1C01850 FOREIGN KEY (script_id) REFERENCES script (id)');
        $this->addSql('DROP TABLE releases');
    }
}
