<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220406083152 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add `crowdfunding_campaign.activity_sector_id` field.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE crowdfunding_campaign ADD activity_sector_id INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE crowdfunding_campaign ADD CONSTRAINT FK_D93EBD72398DEFD0 FOREIGN KEY (activity_sector_id) REFERENCES activity_sector (id) ON DELETE RESTRICT');
        $this->addSql('CREATE INDEX IDX_D93EBD72398DEFD0 ON crowdfunding_campaign (activity_sector_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE crowdfunding_campaign DROP FOREIGN KEY FK_D93EBD72398DEFD0');
        $this->addSql('DROP INDEX IDX_D93EBD72398DEFD0 ON crowdfunding_campaign');
        $this->addSql('ALTER TABLE crowdfunding_campaign DROP activity_sector_id');
    }
}
