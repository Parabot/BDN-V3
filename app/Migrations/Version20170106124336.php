<?php

namespace BDN\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170106124336 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP locked, DROP expires_at, DROP credentials_expire_at, CHANGE salt salt VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE script DROP FOREIGN KEY FK_1C81873AA1C01850');
        $this->addSql('DROP INDEX IDX_1C81873AA1C01850 ON script');
        $this->addSql('ALTER TABLE script CHANGE script_id creator_ida INT DEFAULT NULL');
        $this->addSql('ALTER TABLE script ADD CONSTRAINT FK_1C81873AD7639A90 FOREIGN KEY (creator_ida) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_1C81873AD7639A90 ON script (creator_ida)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE script DROP FOREIGN KEY FK_1C81873AD7639A90');
        $this->addSql('DROP INDEX IDX_1C81873AD7639A90 ON script');
        $this->addSql('ALTER TABLE script CHANGE creator_ida script_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE script ADD CONSTRAINT FK_1C81873AA1C01850 FOREIGN KEY (script_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_1C81873AA1C01850 ON script (script_id)');
        $this->addSql('ALTER TABLE user ADD locked TINYINT(1) NOT NULL, ADD expires_at DATETIME DEFAULT NULL, ADD credentials_expire_at DATETIME DEFAULT NULL, CHANGE salt salt VARCHAR(255) NOT NULL');
    }
}
