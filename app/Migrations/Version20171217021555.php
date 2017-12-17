<?php

namespace BDN\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171217021555 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE server_serveruse');
        $this->addSql('DROP TABLE user_serveruses');
        $this->addSql('ALTER TABLE server_use ADD user_id INT DEFAULT NULL, ADD server_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE server_use ADD CONSTRAINT FK_AA78C611A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE server_use ADD CONSTRAINT FK_AA78C6111844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)');
        $this->addSql('CREATE INDEX IDX_AA78C611A76ED395 ON server_use (user_id)');
        $this->addSql('CREATE INDEX IDX_AA78C6111844E6B7 ON server_use (server_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE server_serveruse (serveruse_id INT NOT NULL, server_id INT NOT NULL, INDEX IDX_28A1B5EAEB1E3248 (serveruse_id), INDEX IDX_28A1B5EA1844E6B7 (server_id), PRIMARY KEY(serveruse_id, server_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_serveruses (serveruse_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_2E513393EB1E3248 (serveruse_id), INDEX IDX_2E513393A76ED395 (user_id), PRIMARY KEY(serveruse_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE server_serveruse ADD CONSTRAINT FK_28A1B5EA1844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)');
        $this->addSql('ALTER TABLE server_serveruse ADD CONSTRAINT FK_28A1B5EAEB1E3248 FOREIGN KEY (serveruse_id) REFERENCES server_use (id)');
        $this->addSql('ALTER TABLE user_serveruses ADD CONSTRAINT FK_2E513393A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_serveruses ADD CONSTRAINT FK_2E513393EB1E3248 FOREIGN KEY (serveruse_id) REFERENCES server_use (id)');
        $this->addSql('ALTER TABLE server_use DROP FOREIGN KEY FK_AA78C611A76ED395');
        $this->addSql('ALTER TABLE server_use DROP FOREIGN KEY FK_AA78C6111844E6B7');
        $this->addSql('DROP INDEX IDX_AA78C611A76ED395 ON server_use');
        $this->addSql('DROP INDEX IDX_AA78C6111844E6B7 ON server_use');
        $this->addSql('ALTER TABLE server_use DROP user_id, DROP server_id');
    }
}
