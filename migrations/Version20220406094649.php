<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220406094649 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Use built in ENUM for `crowdfunding_campaign.status` field.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE crowdfunding_campaign CHANGE status status ENUM(\'drafting\', \'open\', \'collecting_funds\', \'closed\') NOT NULL COMMENT \'(DC2Type:CampaignStatusType)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE crowdfunding_campaign CHANGE status status VARCHAR(30) NOT NULL');
    }
}
