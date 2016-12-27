<?php

namespace BDN\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161103184234 extends AbstractMigration {
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
            'CREATE TABLE server_details (server_id INT NOT NULL, serverdetail_id INT NOT NULL, INDEX IDX_5810361844E6B7 (server_id), INDEX IDX_58103634B5C767 (serverdetail_id), PRIMARY KEY(server_id, serverdetail_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE server_detail (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE server_serveruse (serveruse_id INT NOT NULL, server_id INT NOT NULL, INDEX IDX_28A1B5EAEB1E3248 (serveruse_id), INDEX IDX_28A1B5EA1844E6B7 (server_id), PRIMARY KEY(serveruse_id, server_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql(
            'ALTER TABLE server_details ADD CONSTRAINT FK_5810361844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)'
        );
        $this->addSql(
            'ALTER TABLE server_details ADD CONSTRAINT FK_58103634B5C767 FOREIGN KEY (serverdetail_id) REFERENCES server_detail (id)'
        );
        $this->addSql(
            'ALTER TABLE server_serveruse ADD CONSTRAINT FK_28A1B5EAEB1E3248 FOREIGN KEY (serveruse_id) REFERENCES server_use (id)'
        );
        $this->addSql(
            'ALTER TABLE server_serveruse ADD CONSTRAINT FK_28A1B5EA1844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)'
        );
        $this->addSql('DROP TABLE server_uses');
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

        $this->addSql('ALTER TABLE server_details DROP FOREIGN KEY FK_58103634B5C767');
        $this->addSql(
            'CREATE TABLE server_uses (server_id INT NOT NULL, serveruse_id INT NOT NULL, INDEX IDX_7114973F1844E6B7 (server_id), INDEX IDX_7114973FEB1E3248 (serveruse_id), PRIMARY KEY(server_id, serveruse_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql(
            'ALTER TABLE server_uses ADD CONSTRAINT FK_7114973F1844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)'
        );
        $this->addSql(
            'ALTER TABLE server_uses ADD CONSTRAINT FK_7114973FEB1E3248 FOREIGN KEY (serveruse_id) REFERENCES server_use (id)'
        );
        $this->addSql('DROP TABLE server_details');
        $this->addSql('DROP TABLE server_detail');
        $this->addSql('DROP TABLE server_serveruse');
    }
}
