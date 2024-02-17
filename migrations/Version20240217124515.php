<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240217124515 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paiement DROP CONSTRAINT fk_b1dc7a1ea4aeafea');
        $this->addSql('DROP INDEX idx_b1dc7a1ea4aeafea');
        $this->addSql('ALTER TABLE paiement DROP entreprise_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE paiement ADD entreprise_id INT NOT NULL');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT fk_b1dc7a1ea4aeafea FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_b1dc7a1ea4aeafea ON paiement (entreprise_id)');
    }
}
