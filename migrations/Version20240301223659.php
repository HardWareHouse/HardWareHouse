<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240301223659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entreprise ADD ville VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE entreprise ADD code_postal INT DEFAULT NULL');
        $this->addSql('ALTER TABLE entreprise ADD siren VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE entreprise ADD site_web VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE entreprise ADD email VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE entreprise DROP ville');
        $this->addSql('ALTER TABLE entreprise DROP code_postal');
        $this->addSql('ALTER TABLE entreprise DROP siren');
        $this->addSql('ALTER TABLE entreprise DROP site_web');
        $this->addSql('ALTER TABLE entreprise DROP email');
    }
}
