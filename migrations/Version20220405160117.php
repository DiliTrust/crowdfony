<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220405160117 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add `crowdfunding_campaign` table schema.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE crowdfunding_campaign (id INT AUTO_INCREMENT NOT NULL, company VARCHAR(100) NOT NULL, project VARCHAR(100) NOT NULL, slug VARCHAR(255) NOT NULL, currency VARCHAR(3) DEFAULT \'EUR\' NOT NULL, country VARCHAR(2) DEFAULT \'FR\' NOT NULL, description LONGTEXT DEFAULT NULL, status VARCHAR(30) NOT NULL, min_funding_target INT UNSIGNED DEFAULT NULL, ideal_funding_target INT UNSIGNED DEFAULT NULL, max_funding_target INT UNSIGNED DEFAULT NULL, opening_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', closing_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', timezone VARCHAR(100) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX campaign_slug_unique (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE crowdfunding_campaign');
    }
}
