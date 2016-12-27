<?php

namespace BDN\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161103174525 extends AbstractMigration {
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
            'CREATE TABLE server_groups (server_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_891100B61844E6B7 (server_id), INDEX IDX_891100B6FE54D947 (group_id), PRIMARY KEY(server_id, group_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE server_authors (server_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_FC7231ED1844E6B7 (server_id), INDEX IDX_FC7231EDA76ED395 (user_id), PRIMARY KEY(server_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE server_uses (server_id INT NOT NULL, serveruse_id INT NOT NULL, INDEX IDX_7114973F1844E6B7 (server_id), INDEX IDX_7114973FEB1E3248 (serveruse_id), PRIMARY KEY(server_id, serveruse_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE server_use (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, datetime DATETIME NOT NULL, UNIQUE INDEX UNIQ_AA78C611A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql(
            'ALTER TABLE server_groups ADD CONSTRAINT FK_891100B61844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)'
        );
        $this->addSql(
            'ALTER TABLE server_groups ADD CONSTRAINT FK_891100B6FE54D947 FOREIGN KEY (group_id) REFERENCES user_group (id)'
        );
        $this->addSql(
            'ALTER TABLE server_authors ADD CONSTRAINT FK_FC7231ED1844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)'
        );
        $this->addSql(
            'ALTER TABLE server_authors ADD CONSTRAINT FK_FC7231EDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)'
        );
        $this->addSql(
            'ALTER TABLE server_uses ADD CONSTRAINT FK_7114973F1844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)'
        );
        $this->addSql(
            'ALTER TABLE server_uses ADD CONSTRAINT FK_7114973FEB1E3248 FOREIGN KEY (serveruse_id) REFERENCES server_use (id)'
        );
        $this->addSql(
            'ALTER TABLE server_use ADD CONSTRAINT FK_AA78C611A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)'
        );
        $this->addSql('ALTER TABLE server DROP groups, DROP author');
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

        $this->addSql('ALTER TABLE server_uses DROP FOREIGN KEY FK_7114973FEB1E3248');
        $this->addSql('DROP TABLE server_groups');
        $this->addSql('DROP TABLE server_authors');
        $this->addSql('DROP TABLE server_uses');
        $this->addSql('DROP TABLE server_use');
        $this->addSql(
            'ALTER TABLE server ADD groups LONGTEXT NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\', ADD author LONGTEXT NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\''
        );
    }
}
