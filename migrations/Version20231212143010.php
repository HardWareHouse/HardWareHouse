<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231212143010 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie DROP CONSTRAINT fk_497dd634dac5be2b');
        $this->addSql('DROP INDEX idx_497dd634dac5be2b');
        $this->addSql('ALTER TABLE categorie RENAME COLUMN entreprise_id_id TO entreprise');
        $this->addSql('ALTER TABLE categorie ADD CONSTRAINT FK_497DD634D19FA60 FOREIGN KEY (entreprise) REFERENCES entreprise (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_497DD634D19FA60 ON categorie (entreprise)');
        $this->addSql('ALTER TABLE client DROP CONSTRAINT fk_c7440455dac5be2b');
        $this->addSql('DROP INDEX idx_c7440455dac5be2b');
        $this->addSql('ALTER TABLE client RENAME COLUMN entreprise_id_id TO entreprise');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455D19FA60 FOREIGN KEY (entreprise) REFERENCES entreprise (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_C7440455D19FA60 ON client (entreprise)');
        $this->addSql('ALTER TABLE detail_devis ADD devis INT DEFAULT NULL');
        $this->addSql('ALTER TABLE detail_devis ADD CONSTRAINT FK_F6D70E728B27C52B FOREIGN KEY (devis) REFERENCES devis (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F6D70E728B27C52B ON detail_devis (devis)');
        $this->addSql('ALTER TABLE detail_facture ADD facture INT DEFAULT NULL');
        $this->addSql('ALTER TABLE detail_facture ADD CONSTRAINT FK_9949E4C5FE866410 FOREIGN KEY (facture) REFERENCES facture (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9949E4C5FE866410 ON detail_facture (facture)');
        $this->addSql('ALTER TABLE devis DROP CONSTRAINT fk_8b27c52bdac5be2b');
        $this->addSql('ALTER TABLE devis DROP CONSTRAINT fk_8b27c52bdc2902e0');
        $this->addSql('ALTER TABLE devis DROP CONSTRAINT fk_8b27c52b66cee22');
        $this->addSql('DROP INDEX uniq_8b27c52b66cee22');
        $this->addSql('DROP INDEX idx_8b27c52bdc2902e0');
        $this->addSql('DROP INDEX idx_8b27c52bdac5be2b');
        $this->addSql('ALTER TABLE devis ADD entreprise INT DEFAULT NULL');
        $this->addSql('ALTER TABLE devis ADD client INT DEFAULT NULL');
        $this->addSql('ALTER TABLE devis ADD detaildevis INT DEFAULT NULL');
        $this->addSql('ALTER TABLE devis DROP entreprise_id_id');
        $this->addSql('ALTER TABLE devis DROP client_id_id');
        $this->addSql('ALTER TABLE devis DROP detail_devis_id_id');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52BD19FA60 FOREIGN KEY (entreprise) REFERENCES entreprise (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52BC7440455 FOREIGN KEY (client) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B21575DBF FOREIGN KEY (detaildevis) REFERENCES detail_devis (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8B27C52BD19FA60 ON devis (entreprise)');
        $this->addSql('CREATE INDEX IDX_8B27C52BC7440455 ON devis (client)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8B27C52B21575DBF ON devis (detaildevis)');
        $this->addSql('ALTER TABLE paiement DROP CONSTRAINT fk_b1dc7a1eed7016e0');
        $this->addSql('DROP INDEX idx_b1dc7a1eed7016e0');
        $this->addSql('ALTER TABLE paiement RENAME COLUMN facture_id_id TO facture');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1EFE866410 FOREIGN KEY (facture) REFERENCES facture (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B1DC7A1EFE866410 ON paiement (facture)');
        $this->addSql('ALTER TABLE produit DROP CONSTRAINT fk_29a5ec27dac5be2b');
        $this->addSql('ALTER TABLE produit DROP CONSTRAINT fk_29a5ec278a3c7387');
        $this->addSql('ALTER TABLE produit DROP CONSTRAINT fk_29a5ec2769678373');
        $this->addSql('ALTER TABLE produit DROP CONSTRAINT fk_29a5ec27ed7016e0');
        $this->addSql('DROP INDEX idx_29a5ec27ed7016e0');
        $this->addSql('DROP INDEX idx_29a5ec2769678373');
        $this->addSql('DROP INDEX idx_29a5ec278a3c7387');
        $this->addSql('DROP INDEX idx_29a5ec27dac5be2b');
        $this->addSql('ALTER TABLE produit ADD entreprise INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD categorie INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD devis INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD facture INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit DROP entreprise_id_id');
        $this->addSql('ALTER TABLE produit DROP categorie_id_id');
        $this->addSql('ALTER TABLE produit DROP devis_id_id');
        $this->addSql('ALTER TABLE produit DROP facture_id_id');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27D19FA60 FOREIGN KEY (entreprise) REFERENCES entreprise (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27497DD634 FOREIGN KEY (categorie) REFERENCES categorie (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC278B27C52B FOREIGN KEY (devis) REFERENCES devis (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27FE866410 FOREIGN KEY (facture) REFERENCES facture (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_29A5EC27D19FA60 ON produit (entreprise)');
        $this->addSql('CREATE INDEX IDX_29A5EC27497DD634 ON produit (categorie)');
        $this->addSql('CREATE INDEX IDX_29A5EC278B27C52B ON produit (devis)');
        $this->addSql('CREATE INDEX IDX_29A5EC27FE866410 ON produit (facture)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE devis DROP CONSTRAINT FK_8B27C52BD19FA60');
        $this->addSql('ALTER TABLE devis DROP CONSTRAINT FK_8B27C52BC7440455');
        $this->addSql('ALTER TABLE devis DROP CONSTRAINT FK_8B27C52B21575DBF');
        $this->addSql('DROP INDEX IDX_8B27C52BD19FA60');
        $this->addSql('DROP INDEX IDX_8B27C52BC7440455');
        $this->addSql('DROP INDEX UNIQ_8B27C52B21575DBF');
        $this->addSql('ALTER TABLE devis ADD entreprise_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE devis ADD client_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE devis ADD detail_devis_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE devis DROP entreprise');
        $this->addSql('ALTER TABLE devis DROP client');
        $this->addSql('ALTER TABLE devis DROP detaildevis');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT fk_8b27c52bdac5be2b FOREIGN KEY (entreprise_id_id) REFERENCES entreprise (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT fk_8b27c52bdc2902e0 FOREIGN KEY (client_id_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT fk_8b27c52b66cee22 FOREIGN KEY (detail_devis_id_id) REFERENCES detail_devis (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_8b27c52b66cee22 ON devis (detail_devis_id_id)');
        $this->addSql('CREATE INDEX idx_8b27c52bdc2902e0 ON devis (client_id_id)');
        $this->addSql('CREATE INDEX idx_8b27c52bdac5be2b ON devis (entreprise_id_id)');
        $this->addSql('ALTER TABLE client DROP CONSTRAINT FK_C7440455D19FA60');
        $this->addSql('DROP INDEX IDX_C7440455D19FA60');
        $this->addSql('ALTER TABLE client RENAME COLUMN entreprise TO entreprise_id_id');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT fk_c7440455dac5be2b FOREIGN KEY (entreprise_id_id) REFERENCES entreprise (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_c7440455dac5be2b ON client (entreprise_id_id)');
        $this->addSql('ALTER TABLE categorie DROP CONSTRAINT FK_497DD634D19FA60');
        $this->addSql('DROP INDEX IDX_497DD634D19FA60');
        $this->addSql('ALTER TABLE categorie RENAME COLUMN entreprise TO entreprise_id_id');
        $this->addSql('ALTER TABLE categorie ADD CONSTRAINT fk_497dd634dac5be2b FOREIGN KEY (entreprise_id_id) REFERENCES entreprise (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_497dd634dac5be2b ON categorie (entreprise_id_id)');
        $this->addSql('ALTER TABLE produit DROP CONSTRAINT FK_29A5EC27D19FA60');
        $this->addSql('ALTER TABLE produit DROP CONSTRAINT FK_29A5EC27497DD634');
        $this->addSql('ALTER TABLE produit DROP CONSTRAINT FK_29A5EC278B27C52B');
        $this->addSql('ALTER TABLE produit DROP CONSTRAINT FK_29A5EC27FE866410');
        $this->addSql('DROP INDEX IDX_29A5EC27D19FA60');
        $this->addSql('DROP INDEX IDX_29A5EC27497DD634');
        $this->addSql('DROP INDEX IDX_29A5EC278B27C52B');
        $this->addSql('DROP INDEX IDX_29A5EC27FE866410');
        $this->addSql('ALTER TABLE produit ADD entreprise_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD categorie_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD devis_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD facture_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit DROP entreprise');
        $this->addSql('ALTER TABLE produit DROP categorie');
        $this->addSql('ALTER TABLE produit DROP devis');
        $this->addSql('ALTER TABLE produit DROP facture');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT fk_29a5ec27dac5be2b FOREIGN KEY (entreprise_id_id) REFERENCES entreprise (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT fk_29a5ec278a3c7387 FOREIGN KEY (categorie_id_id) REFERENCES categorie (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT fk_29a5ec2769678373 FOREIGN KEY (devis_id_id) REFERENCES devis (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT fk_29a5ec27ed7016e0 FOREIGN KEY (facture_id_id) REFERENCES facture (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_29a5ec27ed7016e0 ON produit (facture_id_id)');
        $this->addSql('CREATE INDEX idx_29a5ec2769678373 ON produit (devis_id_id)');
        $this->addSql('CREATE INDEX idx_29a5ec278a3c7387 ON produit (categorie_id_id)');
        $this->addSql('CREATE INDEX idx_29a5ec27dac5be2b ON produit (entreprise_id_id)');
        $this->addSql('ALTER TABLE paiement DROP CONSTRAINT FK_B1DC7A1EFE866410');
        $this->addSql('DROP INDEX IDX_B1DC7A1EFE866410');
        $this->addSql('ALTER TABLE paiement RENAME COLUMN facture TO facture_id_id');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT fk_b1dc7a1eed7016e0 FOREIGN KEY (facture_id_id) REFERENCES facture (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_b1dc7a1eed7016e0 ON paiement (facture_id_id)');
        $this->addSql('ALTER TABLE detail_facture DROP CONSTRAINT FK_9949E4C5FE866410');
        $this->addSql('DROP INDEX UNIQ_9949E4C5FE866410');
        $this->addSql('ALTER TABLE detail_facture DROP facture');
        $this->addSql('ALTER TABLE detail_devis DROP CONSTRAINT FK_F6D70E728B27C52B');
        $this->addSql('DROP INDEX UNIQ_F6D70E728B27C52B');
        $this->addSql('ALTER TABLE detail_devis DROP devis');
    }
}
