<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231008134607 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contact ADD account_name VARCHAR(255) NOT NULL, ADD address_line1 VARCHAR(255) NOT NULL, ADD address_line2 VARCHAR(255) DEFAULT NULL, ADD city VARCHAR(255) NOT NULL, ADD contact_name VARCHAR(255) NOT NULL, ADD country VARCHAR(255) NOT NULL, ADD zip_code VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contact DROP account_name, DROP address_line1, DROP address_line2, DROP city, DROP contact_name, DROP country, DROP zip_code');
    }
}
