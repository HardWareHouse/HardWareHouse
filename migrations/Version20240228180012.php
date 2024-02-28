<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240228180012 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE detail_facture ADD produit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE detail_facture ADD CONSTRAINT FK_9949E4C5F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9949E4C5F347EFB ON detail_facture (produit_id)');
        $this->addSql('ALTER TABLE produit DROP CONSTRAINT fk_29a5ec27fe866410');
        $this->addSql('DROP INDEX idx_29a5ec27fe866410');
        $this->addSql('ALTER TABLE produit DROP facture');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE detail_facture DROP CONSTRAINT FK_9949E4C5F347EFB');
        $this->addSql('DROP INDEX IDX_9949E4C5F347EFB');
        $this->addSql('ALTER TABLE detail_facture DROP produit_id');
        $this->addSql('ALTER TABLE produit ADD facture INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT fk_29a5ec27fe866410 FOREIGN KEY (facture) REFERENCES facture (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_29a5ec27fe866410 ON produit (facture)');
    }
}
