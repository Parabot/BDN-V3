<?php

namespace BDN\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20190502073903 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE type_ikov_provider (id INT AUTO_INCREMENT NOT NULL, version VARCHAR(255) NOT NULL, release_date DATETIME NOT NULL, branch VARCHAR(255) NOT NULL, stable TINYINT(1) NOT NULL, build_id INT NOT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cron_task CHANGE lastrun lastrun DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD notes VARCHAR(1000) DEFAULT NULL, DROP additional_information, DROP deleted_at, CHANGE number number VARCHAR(255) DEFAULT NULL, CHANGE completed_at completed_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE oauth2_access_tokens CHANGE user_id user_id INT DEFAULT NULL, CHANGE expires_at expires_at INT DEFAULT NULL, CHANGE scope scope VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE salt salt VARCHAR(255) DEFAULT NULL, CHANGE last_login last_login DATETIME DEFAULT NULL, CHANGE confirmation_token confirmation_token VARCHAR(180) DEFAULT NULL, CHANGE password_requested_at password_requested_at DATETIME DEFAULT NULL, CHANGE api_key api_key VARCHAR(255) DEFAULT NULL, CHANGE google_authenticator_secret google_authenticator_secret VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE oauth2_refresh_tokens CHANGE user_id user_id INT DEFAULT NULL, CHANGE expires_at expires_at INT DEFAULT NULL, CHANGE scope scope VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE slack_invite CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE oauth2_auth_codes CHANGE user_id user_id INT DEFAULT NULL, CHANGE expires_at expires_at INT DEFAULT NULL, CHANGE scope scope VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE login_access_tokens CHANGE user_id user_id INT DEFAULT NULL, CHANGE redirect redirect VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE releases CHANGE script_id script_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE review CHANGE user_id user_id INT DEFAULT NULL, CHANGE script_id script_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE server_use CHANGE user_id user_id INT DEFAULT NULL, CHANGE server_id server_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hook CHANGE server_id server_id INT DEFAULT NULL, CHANGE accessor accessor VARCHAR(255) DEFAULT NULL, CHANGE methodname methodname VARCHAR(255) DEFAULT NULL, CHANGE desctype desctype VARCHAR(255) DEFAULT NULL, CHANGE callclass callclass VARCHAR(255) DEFAULT NULL, CHANGE callmethod callmethod VARCHAR(255) DEFAULT NULL, CHANGE calldesc calldesc VARCHAR(255) DEFAULT NULL, CHANGE callargs callargs VARCHAR(255) DEFAULT NULL, CHANGE field field VARCHAR(255) DEFAULT NULL, CHANGE descfield descfield VARCHAR(255) DEFAULT NULL, CHANGE intoclass intoclass VARCHAR(255) DEFAULT NULL, CHANGE classname classname VARCHAR(255) DEFAULT NULL, CHANGE interface interface VARCHAR(255) DEFAULT NULL, CHANGE invokemethod invokemethod VARCHAR(255) DEFAULT NULL, CHANGE argsdesc argsdesc VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE server CHANGE provider_id provider_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_signature CHANGE abstract_signature_id abstract_signature_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE script CHANGE git_id git_id INT DEFAULT NULL, CHANGE creator_id creator_id INT DEFAULT NULL, CHANGE forum forum INT DEFAULT NULL, CHANGE build_type_id build_type_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_adjustment CHANGE order_id order_id INT DEFAULT NULL, CHANGE order_item_id order_item_id INT DEFAULT NULL, CHANGE order_item_unit_id order_item_unit_id INT DEFAULT NULL, CHANGE `label` `label` VARCHAR(255) DEFAULT NULL, CHANGE origin_id origin_id INT DEFAULT NULL, CHANGE origin_type origin_type VARCHAR(255) DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_order_comment CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_order_identity CHANGE value value VARCHAR(255) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE type_ikov_provider');
        $this->addSql('ALTER TABLE cron_task CHANGE lastrun lastrun DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE hook CHANGE server_id server_id INT DEFAULT NULL, CHANGE accessor accessor VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8_unicode_ci, CHANGE methodname methodname VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8_unicode_ci, CHANGE desctype desctype VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8_unicode_ci, CHANGE callclass callclass VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8_unicode_ci, CHANGE callmethod callmethod VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8_unicode_ci, CHANGE calldesc calldesc VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8_unicode_ci, CHANGE callargs callargs VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8_unicode_ci, CHANGE field field VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8_unicode_ci, CHANGE descfield descfield VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8_unicode_ci, CHANGE intoclass intoclass VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8_unicode_ci, CHANGE classname classname VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8_unicode_ci, CHANGE interface interface VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8_unicode_ci, CHANGE invokemethod invokemethod VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8_unicode_ci, CHANGE argsdesc argsdesc VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE login_access_tokens CHANGE user_id user_id INT DEFAULT NULL, CHANGE redirect redirect VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE oauth2_access_tokens CHANGE user_id user_id INT DEFAULT NULL, CHANGE expires_at expires_at INT DEFAULT NULL, CHANGE scope scope VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE oauth2_auth_codes CHANGE user_id user_id INT DEFAULT NULL, CHANGE expires_at expires_at INT DEFAULT NULL, CHANGE scope scope VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE oauth2_refresh_tokens CHANGE user_id user_id INT DEFAULT NULL, CHANGE expires_at expires_at INT DEFAULT NULL, CHANGE scope scope VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE orders ADD additional_information VARCHAR(1000) DEFAULT \'NULL\' COLLATE utf8_unicode_ci, ADD deleted_at DATETIME DEFAULT \'NULL\', DROP notes, CHANGE number number VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8_unicode_ci, CHANGE completed_at completed_at DATETIME DEFAULT \'NULL\', CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE releases CHANGE script_id script_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE review CHANGE user_id user_id INT DEFAULT NULL, CHANGE script_id script_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE script CHANGE git_id git_id INT DEFAULT NULL, CHANGE creator_id creator_id INT DEFAULT NULL, CHANGE forum forum INT DEFAULT NULL, CHANGE build_type_id build_type_id VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE server CHANGE provider_id provider_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE server_use CHANGE user_id user_id INT DEFAULT NULL, CHANGE server_id server_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE slack_invite CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_adjustment CHANGE order_id order_id INT DEFAULT NULL, CHANGE order_item_id order_item_id INT DEFAULT NULL, CHANGE order_item_unit_id order_item_unit_id INT DEFAULT NULL, CHANGE `label` `label` VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8_unicode_ci, CHANGE origin_id origin_id INT DEFAULT NULL, CHANGE origin_type origin_type VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8_unicode_ci, CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE sylius_order_comment CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE sylius_order_identity CHANGE value value VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE user CHANGE salt salt VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8_unicode_ci, CHANGE last_login last_login DATETIME DEFAULT \'NULL\', CHANGE confirmation_token confirmation_token VARCHAR(180) DEFAULT \'NULL\' COLLATE utf8_unicode_ci, CHANGE password_requested_at password_requested_at DATETIME DEFAULT \'NULL\', CHANGE api_key api_key VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8_unicode_ci, CHANGE google_authenticator_secret google_authenticator_secret VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE user_signature CHANGE abstract_signature_id abstract_signature_id INT DEFAULT NULL');
    }
}
