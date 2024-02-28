<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240228162506 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE detail_devis DROP remise');
        $this->addSql('ALTER TABLE detail_facture DROP CONSTRAINT fk_9949e4c5fe866410');
        $this->addSql('DROP INDEX uniq_9949e4c5fe866410');
        $this->addSql('ALTER TABLE detail_facture DROP facture');
        $this->addSql('ALTER TABLE detail_facture RENAME COLUMN remise TO facture_id');
        $this->addSql('ALTER TABLE detail_facture ADD CONSTRAINT FK_9949E4C57F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9949E4C57F2DEE08 ON detail_facture (facture_id)');
        $this->addSql('ALTER TABLE facture DROP CONSTRAINT fk_fe866410157f9a08');
        $this->addSql('DROP INDEX uniq_fe866410157f9a08');
        $this->addSql('ALTER TABLE facture DROP detailfacture');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE detail_facture DROP CONSTRAINT FK_9949E4C57F2DEE08');
        $this->addSql('DROP INDEX IDX_9949E4C57F2DEE08');
        $this->addSql('ALTER TABLE detail_facture ADD facture INT DEFAULT NULL');
        $this->addSql('ALTER TABLE detail_facture RENAME COLUMN facture_id TO remise');
        $this->addSql('ALTER TABLE detail_facture ADD CONSTRAINT fk_9949e4c5fe866410 FOREIGN KEY (facture) REFERENCES facture (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_9949e4c5fe866410 ON detail_facture (facture)');
        $this->addSql('ALTER TABLE facture ADD detailfacture INT DEFAULT NULL');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT fk_fe866410157f9a08 FOREIGN KEY (detailfacture) REFERENCES detail_facture (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_fe866410157f9a08 ON facture (detailfacture)');
        $this->addSql('ALTER TABLE detail_devis ADD remise INT NOT NULL');
    }
}
