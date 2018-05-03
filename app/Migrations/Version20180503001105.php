<?php declare(strict_types = 1);

namespace BDN\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180503001105 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_adjustment DROP FOREIGN KEY FK_ACA6E0F2E415FB15');
        $this->addSql('ALTER TABLE sylius_order_item_unit DROP FOREIGN KEY FK_82BF226EE415FB15');
        $this->addSql('ALTER TABLE order_items DROP FOREIGN KEY FK_62809DB08D9F6D38');
        $this->addSql('ALTER TABLE sylius_adjustment DROP FOREIGN KEY FK_ACA6E0F28D9F6D38');
        $this->addSql('ALTER TABLE sylius_order_comment DROP FOREIGN KEY FK_8EA9CF098D9F6D38');
        $this->addSql('ALTER TABLE sylius_order_identity DROP FOREIGN KEY FK_5757A18E8D9F6D38');
        $this->addSql('ALTER TABLE sylius_adjustment DROP FOREIGN KEY FK_ACA6E0F2F720C233');
        $this->addSql('DROP TABLE order_items');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE sylius_adjustment');
        $this->addSql('DROP TABLE sylius_order_comment');
        $this->addSql('DROP TABLE sylius_order_identity');
        $this->addSql('DROP TABLE sylius_order_item_unit');
        $this->addSql('DROP TABLE sylius_sequence');
        $this->addSql('ALTER TABLE user DROP google_authenticator_secret');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE order_items (id INT AUTO_INCREMENT NOT NULL, order_id INT NOT NULL, quantity INT NOT NULL, unit_price INT NOT NULL, units_total INT NOT NULL, adjustments_total INT NOT NULL, total INT NOT NULL, is_immutable TINYINT(1) NOT NULL, INDEX IDX_62809DB08D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orders (id INT AUTO_INCREMENT NOT NULL, number VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, additional_information VARCHAR(1000) DEFAULT NULL COLLATE utf8_unicode_ci, state VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, completed_at DATETIME DEFAULT NULL, items_total INT NOT NULL, adjustments_total INT NOT NULL, total INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, email VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, UNIQUE INDEX UNIQ_E52FFDEEE7927C74 (email), UNIQUE INDEX UNIQ_E52FFDEE96901F54 (number), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_adjustment (id INT AUTO_INCREMENT NOT NULL, order_id INT DEFAULT NULL, order_item_id INT DEFAULT NULL, order_item_unit_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, label VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, amount INT NOT NULL, is_neutral TINYINT(1) NOT NULL, is_locked TINYINT(1) NOT NULL, origin_id INT DEFAULT NULL, origin_type VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_ACA6E0F28D9F6D38 (order_id), INDEX IDX_ACA6E0F2E415FB15 (order_item_id), INDEX IDX_ACA6E0F2F720C233 (order_item_unit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_order_comment (id INT AUTO_INCREMENT NOT NULL, order_id INT NOT NULL, state VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, comment LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, notify_customer TINYINT(1) NOT NULL, author VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_8EA9CF098D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_order_identity (id INT AUTO_INCREMENT NOT NULL, order_id INT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, value VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, INDEX IDX_5757A18E8D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_order_item_unit (id INT AUTO_INCREMENT NOT NULL, order_item_id INT NOT NULL, adjustments_total INT NOT NULL, INDEX IDX_82BF226EE415FB15 (order_item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_sequence (id INT AUTO_INCREMENT NOT NULL, idx INT NOT NULL, type VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, UNIQUE INDEX UNIQ_AD3D8CC48CDE5729 (type), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_items ADD CONSTRAINT FK_62809DB08D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_adjustment ADD CONSTRAINT FK_ACA6E0F28D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_adjustment ADD CONSTRAINT FK_ACA6E0F2E415FB15 FOREIGN KEY (order_item_id) REFERENCES order_items (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_adjustment ADD CONSTRAINT FK_ACA6E0F2F720C233 FOREIGN KEY (order_item_unit_id) REFERENCES sylius_order_item_unit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_order_comment ADD CONSTRAINT FK_8EA9CF098D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE sylius_order_identity ADD CONSTRAINT FK_5757A18E8D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE sylius_order_item_unit ADD CONSTRAINT FK_82BF226EE415FB15 FOREIGN KEY (order_item_id) REFERENCES order_items (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD google_authenticator_secret VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
