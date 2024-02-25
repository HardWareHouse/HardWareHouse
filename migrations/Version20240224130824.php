<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240224130824 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE detail_devis ADD produit_id INT NOT NULL');
        $this->addSql('ALTER TABLE detail_devis ADD CONSTRAINT FK_F6D70E72F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_F6D70E72F347EFB ON detail_devis (produit_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE detail_devis DROP CONSTRAINT FK_F6D70E72F347EFB');
        $this->addSql('DROP INDEX IDX_F6D70E72F347EFB');
        $this->addSql('ALTER TABLE detail_devis DROP produit_id');
    }
}
