<?php

namespace BDN\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170402173512 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE SCHEDULED_COMMAND');
        $this->addSql('ALTER TABLE server ADD server_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE server ADD CONSTRAINT FK_5A6DD5F61844E6B7 FOREIGN KEY (server_id) REFERENCES type_provider (id)');
        $this->addSql('CREATE INDEX IDX_5A6DD5F61844E6B7 ON server (server_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE SCHEDULED_COMMAND (ID_SCHEDULED_COMMAND INT AUTO_INCREMENT NOT NULL, NAME VARCHAR(100) NOT NULL COLLATE utf8_unicode_ci, COMMAND VARCHAR(100) NOT NULL COLLATE utf8_unicode_ci, ARGUMENTS VARCHAR(250) DEFAULT NULL COLLATE utf8_unicode_ci, CRON_EXPRESSION VARCHAR(100) DEFAULT NULL COLLATE utf8_unicode_ci, DH_LAST_EXECUTION DATETIME NOT NULL, LAST_RETURN_CODE INT DEFAULT NULL, LOG_FILE VARCHAR(100) DEFAULT NULL COLLATE utf8_unicode_ci, PRIORITY INT NOT NULL, B_EXECUTE_IMMEDIATELY TINYINT(1) NOT NULL, B_DISABLED TINYINT(1) NOT NULL, B_LOCKED TINYINT(1) NOT NULL, PRIMARY KEY(ID_SCHEDULED_COMMAND)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE server DROP FOREIGN KEY FK_5A6DD5F61844E6B7');
        $this->addSql('DROP INDEX IDX_5A6DD5F61844E6B7 ON server');
        $this->addSql('ALTER TABLE server DROP server_id');
    }
}
