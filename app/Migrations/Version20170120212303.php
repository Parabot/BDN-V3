<?php

namespace BDN\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170120212303 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE `release` (id INT AUTO_INCREMENT NOT NULL, script_id INT DEFAULT NULL, changelog LONGTEXT NOT NULL, version DOUBLE PRECISION NOT NULL, date DATETIME NOT NULL, INDEX IDX_9E47031DA1C01850 (script_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `release` ADD CONSTRAINT FK_9E47031DA1C01850 FOREIGN KEY (script_id) REFERENCES script (id)');
        $this->addSql('ALTER TABLE script DROP version');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE `release`');
        $this->addSql('ALTER TABLE script ADD version DOUBLE PRECISION NOT NULL');
    }
}
