<?php

namespace BDN\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170820234613 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE cron_task (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, commands LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', `interval` INT NOT NULL, lastrun DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orders (id INT AUTO_INCREMENT NOT NULL, number VARCHAR(255) DEFAULT NULL, additional_information VARCHAR(1000) DEFAULT NULL, state VARCHAR(255) NOT NULL, completed_at DATETIME DEFAULT NULL, items_total INT NOT NULL, adjustments_total INT NOT NULL, total INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, email VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_E52FFDEE96901F54 (number), UNIQUE INDEX UNIQ_E52FFDEEE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_items (id INT AUTO_INCREMENT NOT NULL, order_id INT NOT NULL, quantity INT NOT NULL, unit_price INT NOT NULL, units_total INT NOT NULL, adjustments_total INT NOT NULL, total INT NOT NULL, is_immutable TINYINT(1) NOT NULL, INDEX IDX_62809DB08D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', community_id INT NOT NULL, UNIQUE INDEX UNIQ_8F02BF9D5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE login_access_tokens (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, t_key VARCHAR(255) NOT NULL, expired TINYINT(1) NOT NULL, date DATETIME NOT NULL, redirect VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_7EC367D32CCFDC5D (t_key), INDEX IDX_7EC367D3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oauth2_access_tokens (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, user_id INT DEFAULT NULL, token VARCHAR(255) NOT NULL, expires_at INT DEFAULT NULL, scope VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_D247A21B5F37A13B (token), INDEX IDX_D247A21B19EB6921 (client_id), INDEX IDX_D247A21BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oauth2_auth_codes (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, user_id INT DEFAULT NULL, token VARCHAR(255) NOT NULL, redirect_uri LONGTEXT NOT NULL, expires_at INT DEFAULT NULL, scope VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_A018A10D5F37A13B (token), INDEX IDX_A018A10D19EB6921 (client_id), INDEX IDX_A018A10DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oauth2_clients (id INT AUTO_INCREMENT NOT NULL, random_id VARCHAR(255) NOT NULL, redirect_uris LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', secret VARCHAR(255) NOT NULL, allowed_grant_types LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oauth2_refresh_tokens (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, user_id INT DEFAULT NULL, token VARCHAR(255) NOT NULL, expires_at INT DEFAULT NULL, scope VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_D394478C5F37A13B (token), INDEX IDX_D394478C19EB6921 (client_id), INDEX IDX_D394478CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE session (id INT AUTO_INCREMENT NOT NULL, ip VARCHAR(45) NOT NULL, date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', api_key VARCHAR(255) DEFAULT NULL, google_authenticator_secret VARCHAR(255) DEFAULT NULL, forums_id INT NOT NULL, forums_access_token INT NOT NULL, community_id INT NOT NULL, UNIQUE INDEX UNIQ_8D93D64992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_8D93D649A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_8D93D649C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_user_group (user_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_28657971A76ED395 (user_id), INDEX IDX_28657971FE54D947 (group_id), PRIMARY KEY(user_id, group_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_oauth_clients (user_id INT NOT NULL, client_id INT NOT NULL, INDEX IDX_FD402C51A76ED395 (user_id), INDEX IDX_FD402C5119EB6921 (client_id), PRIMARY KEY(user_id, client_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE slack_invite (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, date DATETIME NOT NULL, INDEX IDX_738D77CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE language (id INT AUTO_INCREMENT NOT NULL, active TINYINT(1) NOT NULL, language_key VARCHAR(15) NOT NULL, language VARCHAR(64) NOT NULL, UNIQUE INDEX UNIQ_D4DB71B55B160485 (language_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE library (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, version DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE script (id INT AUTO_INCREMENT NOT NULL, git_id INT DEFAULT NULL, creator_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, product LONGTEXT NOT NULL COMMENT \'(DC2Type:object)\', description LONGTEXT NOT NULL, forum INT DEFAULT NULL, active TINYINT(1) NOT NULL, build_type_id VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_1C81873A5E237E06 (name), UNIQUE INDEX UNIQ_1C81873A4D4CA094 (git_id), INDEX IDX_1C81873A61220EA6 (creator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE script_authors (script_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_D304ED1BA1C01850 (script_id), INDEX IDX_D304ED1BA76ED395 (user_id), PRIMARY KEY(script_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE script_users (script_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_78458A76A1C01850 (script_id), INDEX IDX_78458A76A76ED395 (user_id), PRIMARY KEY(script_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE script_groups (script_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_90B1718AA1C01850 (script_id), INDEX IDX_90B1718AFE54D947 (group_id), PRIMARY KEY(script_id, group_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE script_categories (script_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_CD9E8798A1C01850 (script_id), INDEX IDX_CD9E879812469DE2 (category_id), PRIMARY KEY(script_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE git (id INT AUTO_INCREMENT NOT NULL, url VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE releases (id INT AUTO_INCREMENT NOT NULL, script_id INT DEFAULT NULL, changelog LONGTEXT DEFAULT NULL, version DOUBLE PRECISION NOT NULL, date DATETIME NOT NULL, INDEX IDX_7896E4D1A1C01850 (script_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, script_id INT DEFAULT NULL, review LONGTEXT NOT NULL, stars INT NOT NULL, date DATETIME NOT NULL, accepted TINYINT(1) NOT NULL, INDEX IDX_794381C6A76ED395 (user_id), INDEX IDX_794381C6A1C01850 (script_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hook (id INT AUTO_INCREMENT NOT NULL, server_id INT DEFAULT NULL, accessor VARCHAR(255) DEFAULT NULL, methodname VARCHAR(255) DEFAULT NULL, desctype VARCHAR(255) DEFAULT NULL, callclass VARCHAR(255) DEFAULT NULL, callmethod VARCHAR(255) DEFAULT NULL, calldesc VARCHAR(255) DEFAULT NULL, callargs VARCHAR(255) DEFAULT NULL, field VARCHAR(255) DEFAULT NULL, descfield VARCHAR(255) DEFAULT NULL, intoclass VARCHAR(255) DEFAULT NULL, classname VARCHAR(255) DEFAULT NULL, interface VARCHAR(255) DEFAULT NULL, invokemethod VARCHAR(255) DEFAULT NULL, argsdesc VARCHAR(255) DEFAULT NULL, type VARCHAR(255) NOT NULL, version DOUBLE PRECISION NOT NULL, INDEX IDX_A45843551844E6B7 (server_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE server (id INT AUTO_INCREMENT NOT NULL, server_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, version DOUBLE PRECISION NOT NULL, description LONGTEXT NOT NULL, provider_id INT DEFAULT NULL, INDEX IDX_5A6DD5F6A53A8AA (provider_id), UNIQUE INDEX UNIQ_5A6DD5F61844E6B7 (server_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE server_groups (server_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_891100B61844E6B7 (server_id), INDEX IDX_891100B6FE54D947 (group_id), PRIMARY KEY(server_id, group_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE server_authors (server_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_FC7231ED1844E6B7 (server_id), INDEX IDX_FC7231EDA76ED395 (user_id), PRIMARY KEY(server_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE server_details (server_id INT NOT NULL, serverdetail_id INT NOT NULL, INDEX IDX_5810361844E6B7 (server_id), INDEX IDX_58103634B5C767 (serverdetail_id), PRIMARY KEY(server_id, serverdetail_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE server_detail (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE server_slack_channel (id INT AUTO_INCREMENT NOT NULL, channel VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE server_use (id INT AUTO_INCREMENT NOT NULL, datetime DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_serveruses (serveruse_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_2E513393EB1E3248 (serveruse_id), INDEX IDX_2E513393A76ED395 (user_id), PRIMARY KEY(serveruse_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE server_serveruse (serveruse_id INT NOT NULL, server_id INT NOT NULL, INDEX IDX_28A1B5EAEB1E3248 (serveruse_id), INDEX IDX_28A1B5EA1844E6B7 (server_id), PRIMARY KEY(serveruse_id, server_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_signature (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, abstract_signature_id INT DEFAULT NULL, INDEX IDX_AE688CA57E0AA10B (abstract_signature_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_client (id INT AUTO_INCREMENT NOT NULL, version VARCHAR(255) NOT NULL, release_date DATETIME NOT NULL, branch VARCHAR(255) NOT NULL, stable TINYINT(1) NOT NULL, build_id INT NOT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_default_provider (id INT AUTO_INCREMENT NOT NULL, version VARCHAR(255) NOT NULL, release_date DATETIME NOT NULL, branch VARCHAR(255) NOT NULL, stable TINYINT(1) NOT NULL, build_id INT NOT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_dreamscape_provider (id INT AUTO_INCREMENT NOT NULL, version VARCHAR(255) NOT NULL, release_date DATETIME NOT NULL, branch VARCHAR(255) NOT NULL, stable TINYINT(1) NOT NULL, build_id INT NOT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_osscape_provider (id INT AUTO_INCREMENT NOT NULL, version VARCHAR(255) NOT NULL, release_date DATETIME NOT NULL, branch VARCHAR(255) NOT NULL, stable TINYINT(1) NOT NULL, build_id INT NOT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_pkhonor_provider (id INT AUTO_INCREMENT NOT NULL, version VARCHAR(255) NOT NULL, release_date DATETIME NOT NULL, branch VARCHAR(255) NOT NULL, stable TINYINT(1) NOT NULL, build_id INT NOT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_randoms (id INT AUTO_INCREMENT NOT NULL, version VARCHAR(255) NOT NULL, release_date DATETIME NOT NULL, branch VARCHAR(255) NOT NULL, stable TINYINT(1) NOT NULL, build_id INT NOT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_sequence (id INT AUTO_INCREMENT NOT NULL, idx INT NOT NULL, type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_AD3D8CC48CDE5729 (type), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_adjustment (id INT AUTO_INCREMENT NOT NULL, order_id INT DEFAULT NULL, order_item_id INT DEFAULT NULL, order_item_unit_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, label VARCHAR(255) DEFAULT NULL, amount INT NOT NULL, is_neutral TINYINT(1) NOT NULL, is_locked TINYINT(1) NOT NULL, origin_id INT DEFAULT NULL, origin_type VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_ACA6E0F28D9F6D38 (order_id), INDEX IDX_ACA6E0F2E415FB15 (order_item_id), INDEX IDX_ACA6E0F2F720C233 (order_item_unit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_order_comment (id INT AUTO_INCREMENT NOT NULL, order_id INT NOT NULL, state VARCHAR(255) NOT NULL, comment LONGTEXT DEFAULT NULL, notify_customer TINYINT(1) NOT NULL, author VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_8EA9CF098D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_order_identity (id INT AUTO_INCREMENT NOT NULL, order_id INT NOT NULL, name VARCHAR(255) NOT NULL, value VARCHAR(255) DEFAULT NULL, INDEX IDX_5757A18E8D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_order_item_unit (id INT AUTO_INCREMENT NOT NULL, order_item_id INT NOT NULL, adjustments_total INT NOT NULL, INDEX IDX_82BF226EE415FB15 (order_item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_items ADD CONSTRAINT FK_62809DB08D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE login_access_tokens ADD CONSTRAINT FK_7EC367D3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE oauth2_access_tokens ADD CONSTRAINT FK_D247A21B19EB6921 FOREIGN KEY (client_id) REFERENCES oauth2_clients (id)');
        $this->addSql('ALTER TABLE oauth2_access_tokens ADD CONSTRAINT FK_D247A21BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE oauth2_auth_codes ADD CONSTRAINT FK_A018A10D19EB6921 FOREIGN KEY (client_id) REFERENCES oauth2_clients (id)');
        $this->addSql('ALTER TABLE oauth2_auth_codes ADD CONSTRAINT FK_A018A10DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE oauth2_refresh_tokens ADD CONSTRAINT FK_D394478C19EB6921 FOREIGN KEY (client_id) REFERENCES oauth2_clients (id)');
        $this->addSql('ALTER TABLE oauth2_refresh_tokens ADD CONSTRAINT FK_D394478CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_user_group ADD CONSTRAINT FK_28657971A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_user_group ADD CONSTRAINT FK_28657971FE54D947 FOREIGN KEY (group_id) REFERENCES user_group (id)');
        $this->addSql('ALTER TABLE user_oauth_clients ADD CONSTRAINT FK_FD402C51A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_oauth_clients ADD CONSTRAINT FK_FD402C5119EB6921 FOREIGN KEY (client_id) REFERENCES oauth2_clients (id)');
        $this->addSql('ALTER TABLE slack_invite ADD CONSTRAINT FK_738D77CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE script ADD CONSTRAINT FK_1C81873A4D4CA094 FOREIGN KEY (git_id) REFERENCES git (id)');
        $this->addSql('ALTER TABLE script ADD CONSTRAINT FK_1C81873A61220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE script_authors ADD CONSTRAINT FK_D304ED1BA1C01850 FOREIGN KEY (script_id) REFERENCES script (id)');
        $this->addSql('ALTER TABLE script_authors ADD CONSTRAINT FK_D304ED1BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE script_users ADD CONSTRAINT FK_78458A76A1C01850 FOREIGN KEY (script_id) REFERENCES script (id)');
        $this->addSql('ALTER TABLE script_users ADD CONSTRAINT FK_78458A76A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE script_groups ADD CONSTRAINT FK_90B1718AA1C01850 FOREIGN KEY (script_id) REFERENCES script (id)');
        $this->addSql('ALTER TABLE script_groups ADD CONSTRAINT FK_90B1718AFE54D947 FOREIGN KEY (group_id) REFERENCES user_group (id)');
        $this->addSql('ALTER TABLE script_categories ADD CONSTRAINT FK_CD9E8798A1C01850 FOREIGN KEY (script_id) REFERENCES script (id)');
        $this->addSql('ALTER TABLE script_categories ADD CONSTRAINT FK_CD9E879812469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE releases ADD CONSTRAINT FK_7896E4D1A1C01850 FOREIGN KEY (script_id) REFERENCES script (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A1C01850 FOREIGN KEY (script_id) REFERENCES script (id)');
        $this->addSql('ALTER TABLE hook ADD CONSTRAINT FK_A45843551844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)');
        $this->addSql('ALTER TABLE server ADD CONSTRAINT FK_5A6DD5F61844E6B7 FOREIGN KEY (server_id) REFERENCES server_slack_channel (id)');
        $this->addSql('ALTER TABLE server_groups ADD CONSTRAINT FK_891100B61844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)');
        $this->addSql('ALTER TABLE server_groups ADD CONSTRAINT FK_891100B6FE54D947 FOREIGN KEY (group_id) REFERENCES user_group (id)');
        $this->addSql('ALTER TABLE server_authors ADD CONSTRAINT FK_FC7231ED1844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)');
        $this->addSql('ALTER TABLE server_authors ADD CONSTRAINT FK_FC7231EDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE server_details ADD CONSTRAINT FK_5810361844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)');
        $this->addSql('ALTER TABLE server_details ADD CONSTRAINT FK_58103634B5C767 FOREIGN KEY (serverdetail_id) REFERENCES server_detail (id)');
        $this->addSql('ALTER TABLE user_serveruses ADD CONSTRAINT FK_2E513393EB1E3248 FOREIGN KEY (serveruse_id) REFERENCES server_use (id)');
        $this->addSql('ALTER TABLE user_serveruses ADD CONSTRAINT FK_2E513393A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE server_serveruse ADD CONSTRAINT FK_28A1B5EAEB1E3248 FOREIGN KEY (serveruse_id) REFERENCES server_use (id)');
        $this->addSql('ALTER TABLE server_serveruse ADD CONSTRAINT FK_28A1B5EA1844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)');
        $this->addSql('ALTER TABLE sylius_adjustment ADD CONSTRAINT FK_ACA6E0F28D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_adjustment ADD CONSTRAINT FK_ACA6E0F2E415FB15 FOREIGN KEY (order_item_id) REFERENCES order_items (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_adjustment ADD CONSTRAINT FK_ACA6E0F2F720C233 FOREIGN KEY (order_item_unit_id) REFERENCES sylius_order_item_unit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_order_comment ADD CONSTRAINT FK_8EA9CF098D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE sylius_order_identity ADD CONSTRAINT FK_5757A18E8D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE sylius_order_item_unit ADD CONSTRAINT FK_82BF226EE415FB15 FOREIGN KEY (order_item_id) REFERENCES order_items (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_items DROP FOREIGN KEY FK_62809DB08D9F6D38');
        $this->addSql('ALTER TABLE sylius_adjustment DROP FOREIGN KEY FK_ACA6E0F28D9F6D38');
        $this->addSql('ALTER TABLE sylius_order_comment DROP FOREIGN KEY FK_8EA9CF098D9F6D38');
        $this->addSql('ALTER TABLE sylius_order_identity DROP FOREIGN KEY FK_5757A18E8D9F6D38');
        $this->addSql('ALTER TABLE sylius_adjustment DROP FOREIGN KEY FK_ACA6E0F2E415FB15');
        $this->addSql('ALTER TABLE sylius_order_item_unit DROP FOREIGN KEY FK_82BF226EE415FB15');
        $this->addSql('ALTER TABLE user_user_group DROP FOREIGN KEY FK_28657971FE54D947');
        $this->addSql('ALTER TABLE script_groups DROP FOREIGN KEY FK_90B1718AFE54D947');
        $this->addSql('ALTER TABLE server_groups DROP FOREIGN KEY FK_891100B6FE54D947');
        $this->addSql('ALTER TABLE oauth2_access_tokens DROP FOREIGN KEY FK_D247A21B19EB6921');
        $this->addSql('ALTER TABLE oauth2_auth_codes DROP FOREIGN KEY FK_A018A10D19EB6921');
        $this->addSql('ALTER TABLE oauth2_refresh_tokens DROP FOREIGN KEY FK_D394478C19EB6921');
        $this->addSql('ALTER TABLE user_oauth_clients DROP FOREIGN KEY FK_FD402C5119EB6921');
        $this->addSql('ALTER TABLE login_access_tokens DROP FOREIGN KEY FK_7EC367D3A76ED395');
        $this->addSql('ALTER TABLE oauth2_access_tokens DROP FOREIGN KEY FK_D247A21BA76ED395');
        $this->addSql('ALTER TABLE oauth2_auth_codes DROP FOREIGN KEY FK_A018A10DA76ED395');
        $this->addSql('ALTER TABLE oauth2_refresh_tokens DROP FOREIGN KEY FK_D394478CA76ED395');
        $this->addSql('ALTER TABLE user_user_group DROP FOREIGN KEY FK_28657971A76ED395');
        $this->addSql('ALTER TABLE user_oauth_clients DROP FOREIGN KEY FK_FD402C51A76ED395');
        $this->addSql('ALTER TABLE slack_invite DROP FOREIGN KEY FK_738D77CA76ED395');
        $this->addSql('ALTER TABLE script DROP FOREIGN KEY FK_1C81873A61220EA6');
        $this->addSql('ALTER TABLE script_authors DROP FOREIGN KEY FK_D304ED1BA76ED395');
        $this->addSql('ALTER TABLE script_users DROP FOREIGN KEY FK_78458A76A76ED395');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6A76ED395');
        $this->addSql('ALTER TABLE server_authors DROP FOREIGN KEY FK_FC7231EDA76ED395');
        $this->addSql('ALTER TABLE user_serveruses DROP FOREIGN KEY FK_2E513393A76ED395');
        $this->addSql('ALTER TABLE script_authors DROP FOREIGN KEY FK_D304ED1BA1C01850');
        $this->addSql('ALTER TABLE script_users DROP FOREIGN KEY FK_78458A76A1C01850');
        $this->addSql('ALTER TABLE script_groups DROP FOREIGN KEY FK_90B1718AA1C01850');
        $this->addSql('ALTER TABLE script_categories DROP FOREIGN KEY FK_CD9E8798A1C01850');
        $this->addSql('ALTER TABLE releases DROP FOREIGN KEY FK_7896E4D1A1C01850');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6A1C01850');
        $this->addSql('ALTER TABLE script_categories DROP FOREIGN KEY FK_CD9E879812469DE2');
        $this->addSql('ALTER TABLE script DROP FOREIGN KEY FK_1C81873A4D4CA094');
        $this->addSql('ALTER TABLE hook DROP FOREIGN KEY FK_A45843551844E6B7');
        $this->addSql('ALTER TABLE server_groups DROP FOREIGN KEY FK_891100B61844E6B7');
        $this->addSql('ALTER TABLE server_authors DROP FOREIGN KEY FK_FC7231ED1844E6B7');
        $this->addSql('ALTER TABLE server_details DROP FOREIGN KEY FK_5810361844E6B7');
        $this->addSql('ALTER TABLE server_serveruse DROP FOREIGN KEY FK_28A1B5EA1844E6B7');
        $this->addSql('ALTER TABLE server_details DROP FOREIGN KEY FK_58103634B5C767');
        $this->addSql('ALTER TABLE server DROP FOREIGN KEY FK_5A6DD5F61844E6B7');
        $this->addSql('ALTER TABLE user_serveruses DROP FOREIGN KEY FK_2E513393EB1E3248');
        $this->addSql('ALTER TABLE server_serveruse DROP FOREIGN KEY FK_28A1B5EAEB1E3248');
        $this->addSql('ALTER TABLE sylius_adjustment DROP FOREIGN KEY FK_ACA6E0F2F720C233');
        $this->addSql('DROP TABLE cron_task');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE order_items');
        $this->addSql('DROP TABLE user_group');
        $this->addSql('DROP TABLE login_access_tokens');
        $this->addSql('DROP TABLE oauth2_access_tokens');
        $this->addSql('DROP TABLE oauth2_auth_codes');
        $this->addSql('DROP TABLE oauth2_clients');
        $this->addSql('DROP TABLE oauth2_refresh_tokens');
        $this->addSql('DROP TABLE session');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_user_group');
        $this->addSql('DROP TABLE user_oauth_clients');
        $this->addSql('DROP TABLE slack_invite');
        $this->addSql('DROP TABLE language');
        $this->addSql('DROP TABLE library');
        $this->addSql('DROP TABLE script');
        $this->addSql('DROP TABLE script_authors');
        $this->addSql('DROP TABLE script_users');
        $this->addSql('DROP TABLE script_groups');
        $this->addSql('DROP TABLE script_categories');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE git');
        $this->addSql('DROP TABLE releases');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE hook');
        $this->addSql('DROP TABLE server');
        $this->addSql('DROP TABLE server_groups');
        $this->addSql('DROP TABLE server_authors');
        $this->addSql('DROP TABLE server_details');
        $this->addSql('DROP TABLE server_detail');
        $this->addSql('DROP TABLE server_slack_channel');
        $this->addSql('DROP TABLE server_use');
        $this->addSql('DROP TABLE user_serveruses');
        $this->addSql('DROP TABLE server_serveruse');
        $this->addSql('DROP TABLE user_signature');
        $this->addSql('DROP TABLE type_client');
        $this->addSql('DROP TABLE type_default_provider');
        $this->addSql('DROP TABLE type_dreamscape_provider');
        $this->addSql('DROP TABLE type_osscape_provider');
        $this->addSql('DROP TABLE type_pkhonor_provider');
        $this->addSql('DROP TABLE type_randoms');
        $this->addSql('DROP TABLE sylius_sequence');
        $this->addSql('DROP TABLE sylius_adjustment');
        $this->addSql('DROP TABLE sylius_order_comment');
        $this->addSql('DROP TABLE sylius_order_identity');
        $this->addSql('DROP TABLE sylius_order_item_unit');
    }
}
