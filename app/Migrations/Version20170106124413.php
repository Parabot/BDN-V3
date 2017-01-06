<?php

namespace BDN\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170106124413 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE script DROP FOREIGN KEY FK_1C81873AD7639A90');
        $this->addSql('DROP INDEX IDX_1C81873AD7639A90 ON script');
        $this->addSql('ALTER TABLE script CHANGE creator_ida creator_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE script ADD CONSTRAINT FK_1C81873A61220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_1C81873A61220EA6 ON script (creator_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE script DROP FOREIGN KEY FK_1C81873A61220EA6');
        $this->addSql('DROP INDEX IDX_1C81873A61220EA6 ON script');
        $this->addSql('ALTER TABLE script CHANGE creator_id creator_ida INT DEFAULT NULL');
        $this->addSql('ALTER TABLE script ADD CONSTRAINT FK_1C81873AD7639A90 FOREIGN KEY (creator_ida) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_1C81873AD7639A90 ON script (creator_ida)');
    }
}
