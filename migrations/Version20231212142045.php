<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231212142045 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE categorie_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE client_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE detail_devis_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE detail_facture_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE devis_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE entreprise_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE facture_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE paiement_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE produit_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE categorie (id INT NOT NULL, entreprise_id_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, description TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_497DD634DAC5BE2B ON categorie (entreprise_id_id)');
        $this->addSql('COMMENT ON COLUMN categorie.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE client (id INT NOT NULL, entreprise_id_id INT NOT NULL, nom VARCHAR(255) NOT NULL, adresse TEXT NOT NULL, email VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C7440455DAC5BE2B ON client (entreprise_id_id)');
        $this->addSql('COMMENT ON COLUMN client.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE detail_devis (id INT NOT NULL, quantite INT NOT NULL, prix DOUBLE PRECISION NOT NULL, remise INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN detail_devis.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE detail_facture (id INT NOT NULL, quantite INT NOT NULL, prix DOUBLE PRECISION NOT NULL, remise INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN detail_facture.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE devis (id INT NOT NULL, entreprise_id_id INT DEFAULT NULL, client_id_id INT DEFAULT NULL, detail_devis_id_id INT DEFAULT NULL, numero VARCHAR(255) NOT NULL, date_creation TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(255) NOT NULL, total DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8B27C52BDAC5BE2B ON devis (entreprise_id_id)');
        $this->addSql('CREATE INDEX IDX_8B27C52BDC2902E0 ON devis (client_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8B27C52B66CEE22 ON devis (detail_devis_id_id)');
        $this->addSql('COMMENT ON COLUMN devis.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE entreprise (id INT NOT NULL, user_uuid UUID NOT NULL, nom VARCHAR(255) NOT NULL, adresse TEXT NOT NULL, description TEXT NOT NULL, information_fiscale VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D19FA60ABFE1C6F ON entreprise (user_uuid)');
        $this->addSql('COMMENT ON COLUMN entreprise.user_uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN entreprise.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE facture (id INT NOT NULL, entreprise_id_id INT DEFAULT NULL, client_id_id INT DEFAULT NULL, detail_facture_id_id INT DEFAULT NULL, numero VARCHAR(255) NOT NULL, date_facturation TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, date_paiement_due TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, statut_paiement VARCHAR(255) NOT NULL, total DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FE866410DAC5BE2B ON facture (entreprise_id_id)');
        $this->addSql('CREATE INDEX IDX_FE866410DC2902E0 ON facture (client_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FE8664107DD3081B ON facture (detail_facture_id_id)');
        $this->addSql('COMMENT ON COLUMN facture.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE paiement (id INT NOT NULL, facture_id_id INT DEFAULT NULL, date_paiement TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, montant DOUBLE PRECISION NOT NULL, methode_paiement VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B1DC7A1EED7016E0 ON paiement (facture_id_id)');
        $this->addSql('CREATE TABLE produit (id INT NOT NULL, entreprise_id_id INT DEFAULT NULL, categorie_id_id INT DEFAULT NULL, devis_id_id INT DEFAULT NULL, facture_id_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, description TEXT NOT NULL, prix DOUBLE PRECISION NOT NULL, stock INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_29A5EC27DAC5BE2B ON produit (entreprise_id_id)');
        $this->addSql('CREATE INDEX IDX_29A5EC278A3C7387 ON produit (categorie_id_id)');
        $this->addSql('CREATE INDEX IDX_29A5EC2769678373 ON produit (devis_id_id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27ED7016E0 ON produit (facture_id_id)');
        $this->addSql('COMMENT ON COLUMN produit.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "user" (uuid UUID NOT NULL, roles JSON DEFAULT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_verified BOOLEAN NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('COMMENT ON COLUMN "user".uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE categorie ADD CONSTRAINT FK_497DD634DAC5BE2B FOREIGN KEY (entreprise_id_id) REFERENCES entreprise (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455DAC5BE2B FOREIGN KEY (entreprise_id_id) REFERENCES entreprise (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52BDAC5BE2B FOREIGN KEY (entreprise_id_id) REFERENCES entreprise (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52BDC2902E0 FOREIGN KEY (client_id_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B66CEE22 FOREIGN KEY (detail_devis_id_id) REFERENCES detail_devis (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE entreprise ADD CONSTRAINT FK_D19FA60ABFE1C6F FOREIGN KEY (user_uuid) REFERENCES "user" (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410DAC5BE2B FOREIGN KEY (entreprise_id_id) REFERENCES entreprise (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410DC2902E0 FOREIGN KEY (client_id_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE8664107DD3081B FOREIGN KEY (detail_facture_id_id) REFERENCES detail_facture (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1EED7016E0 FOREIGN KEY (facture_id_id) REFERENCES facture (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27DAC5BE2B FOREIGN KEY (entreprise_id_id) REFERENCES entreprise (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC278A3C7387 FOREIGN KEY (categorie_id_id) REFERENCES categorie (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC2769678373 FOREIGN KEY (devis_id_id) REFERENCES devis (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27ED7016E0 FOREIGN KEY (facture_id_id) REFERENCES facture (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE categorie_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE client_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE detail_devis_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE detail_facture_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE devis_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE entreprise_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE facture_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE paiement_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE produit_id_seq CASCADE');
        $this->addSql('ALTER TABLE categorie DROP CONSTRAINT FK_497DD634DAC5BE2B');
        $this->addSql('ALTER TABLE client DROP CONSTRAINT FK_C7440455DAC5BE2B');
        $this->addSql('ALTER TABLE devis DROP CONSTRAINT FK_8B27C52BDAC5BE2B');
        $this->addSql('ALTER TABLE devis DROP CONSTRAINT FK_8B27C52BDC2902E0');
        $this->addSql('ALTER TABLE devis DROP CONSTRAINT FK_8B27C52B66CEE22');
        $this->addSql('ALTER TABLE entreprise DROP CONSTRAINT FK_D19FA60ABFE1C6F');
        $this->addSql('ALTER TABLE facture DROP CONSTRAINT FK_FE866410DAC5BE2B');
        $this->addSql('ALTER TABLE facture DROP CONSTRAINT FK_FE866410DC2902E0');
        $this->addSql('ALTER TABLE facture DROP CONSTRAINT FK_FE8664107DD3081B');
        $this->addSql('ALTER TABLE paiement DROP CONSTRAINT FK_B1DC7A1EED7016E0');
        $this->addSql('ALTER TABLE produit DROP CONSTRAINT FK_29A5EC27DAC5BE2B');
        $this->addSql('ALTER TABLE produit DROP CONSTRAINT FK_29A5EC278A3C7387');
        $this->addSql('ALTER TABLE produit DROP CONSTRAINT FK_29A5EC2769678373');
        $this->addSql('ALTER TABLE produit DROP CONSTRAINT FK_29A5EC27ED7016E0');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE detail_devis');
        $this->addSql('DROP TABLE detail_facture');
        $this->addSql('DROP TABLE devis');
        $this->addSql('DROP TABLE entreprise');
        $this->addSql('DROP TABLE facture');
        $this->addSql('DROP TABLE paiement');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
