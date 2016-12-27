<?php

namespace BDN\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161103174752 extends AbstractMigration {
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema) {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql(
            'CREATE TABLE user_serveruses (serveruse_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_2E513393EB1E3248 (serveruse_id), INDEX IDX_2E513393A76ED395 (user_id), PRIMARY KEY(serveruse_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql(
            'ALTER TABLE user_serveruses ADD CONSTRAINT FK_2E513393EB1E3248 FOREIGN KEY (serveruse_id) REFERENCES server_use (id)'
        );
        $this->addSql(
            'ALTER TABLE user_serveruses ADD CONSTRAINT FK_2E513393A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)'
        );
        $this->addSql('ALTER TABLE server_use DROP FOREIGN KEY FK_AA78C611A76ED395');
        $this->addSql('DROP INDEX UNIQ_AA78C611A76ED395 ON server_use');
        $this->addSql('ALTER TABLE server_use DROP user_id');
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

        $this->addSql('DROP TABLE user_serveruses');
        $this->addSql('ALTER TABLE server_use ADD user_id INT DEFAULT NULL');
        $this->addSql(
            'ALTER TABLE server_use ADD CONSTRAINT FK_AA78C611A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)'
        );
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AA78C611A76ED395 ON server_use (user_id)');
    }
}
