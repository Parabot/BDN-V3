<?php

namespace BDN\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161230190523 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE user_script');
        $this->addSql('ALTER TABLE script ADD script_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE script ADD CONSTRAINT FK_1C81873AA1C01850 FOREIGN KEY (script_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_1C81873AA1C01850 ON script (script_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_script (user_id INT NOT NULL, script_id INT NOT NULL, INDEX IDX_79D39C07A76ED395 (user_id), INDEX IDX_79D39C07A1C01850 (script_id), PRIMARY KEY(user_id, script_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_script ADD CONSTRAINT FK_79D39C07A1C01850 FOREIGN KEY (script_id) REFERENCES script (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_script ADD CONSTRAINT FK_79D39C07A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE script DROP FOREIGN KEY FK_1C81873AA1C01850');
        $this->addSql('DROP INDEX IDX_1C81873AA1C01850 ON script');
        $this->addSql('ALTER TABLE script DROP script_id');
    }
}
