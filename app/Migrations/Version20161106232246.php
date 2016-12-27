<?php

namespace BDN\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161106232246 extends AbstractMigration {
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema) {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE hook ADD server_id INT DEFAULT NULL');
        $this->addSql(
            'ALTER TABLE hook ADD CONSTRAINT FK_A45843551844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)'
        );
        $this->addSql('CREATE INDEX IDX_A45843551844E6B7 ON hook (server_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema) {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE hook DROP FOREIGN KEY FK_A45843551844E6B7');
        $this->addSql('DROP INDEX IDX_A45843551844E6B7 ON hook');
        $this->addSql('ALTER TABLE hook DROP server_id');
    }
}
