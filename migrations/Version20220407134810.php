<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220407134810 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add `fund_investment` table schema.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE fund_investment (id INT UNSIGNED AUTO_INCREMENT NOT NULL, campaign_id INT NOT NULL, investor_id INT NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', equity_amount INT UNSIGNED NOT NULL, processing_fee_amount INT UNSIGNED DEFAULT 0 NOT NULL, total_charged_amount INT UNSIGNED NOT NULL, status VARCHAR(255) NOT NULL, credit_card_token VARCHAR(255) DEFAULT NULL, charge_transaction_id VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', charged_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', canceled_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', refunded_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_4D9C8505F639F774 (campaign_id), INDEX IDX_4D9C85059AE528DA (investor_id), UNIQUE INDEX fund_investment_uuid_unique (uuid), UNIQUE INDEX fund_investment_charge_transaction_id_unique (charge_transaction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fund_investment ADD CONSTRAINT FK_4D9C8505F639F774 FOREIGN KEY (campaign_id) REFERENCES crowdfunding_campaign (id) ON DELETE RESTRICT');
        $this->addSql('ALTER TABLE fund_investment ADD CONSTRAINT FK_4D9C85059AE528DA FOREIGN KEY (investor_id) REFERENCES user (id) ON DELETE RESTRICT');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE fund_investment');
    }
}
