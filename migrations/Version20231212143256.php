<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231212143256 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture DROP CONSTRAINT fk_fe866410dac5be2b');
        $this->addSql('ALTER TABLE facture DROP CONSTRAINT fk_fe866410dc2902e0');
        $this->addSql('ALTER TABLE facture DROP CONSTRAINT fk_fe8664107dd3081b');
        $this->addSql('DROP INDEX uniq_fe8664107dd3081b');
        $this->addSql('DROP INDEX idx_fe866410dc2902e0');
        $this->addSql('DROP INDEX idx_fe866410dac5be2b');
        $this->addSql('ALTER TABLE facture ADD entreprise INT DEFAULT NULL');
        $this->addSql('ALTER TABLE facture ADD client INT DEFAULT NULL');
        $this->addSql('ALTER TABLE facture ADD detailfacture INT DEFAULT NULL');
        $this->addSql('ALTER TABLE facture DROP entreprise_id_id');
        $this->addSql('ALTER TABLE facture DROP client_id_id');
        $this->addSql('ALTER TABLE facture DROP detail_facture_id_id');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410D19FA60 FOREIGN KEY (entreprise) REFERENCES entreprise (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410C7440455 FOREIGN KEY (client) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410157F9A08 FOREIGN KEY (detailfacture) REFERENCES detail_facture (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_FE866410D19FA60 ON facture (entreprise)');
        $this->addSql('CREATE INDEX IDX_FE866410C7440455 ON facture (client)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FE866410157F9A08 ON facture (detailfacture)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE facture DROP CONSTRAINT FK_FE866410D19FA60');
        $this->addSql('ALTER TABLE facture DROP CONSTRAINT FK_FE866410C7440455');
        $this->addSql('ALTER TABLE facture DROP CONSTRAINT FK_FE866410157F9A08');
        $this->addSql('DROP INDEX IDX_FE866410D19FA60');
        $this->addSql('DROP INDEX IDX_FE866410C7440455');
        $this->addSql('DROP INDEX UNIQ_FE866410157F9A08');
        $this->addSql('ALTER TABLE facture ADD entreprise_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE facture ADD client_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE facture ADD detail_facture_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE facture DROP entreprise');
        $this->addSql('ALTER TABLE facture DROP client');
        $this->addSql('ALTER TABLE facture DROP detailfacture');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT fk_fe866410dac5be2b FOREIGN KEY (entreprise_id_id) REFERENCES entreprise (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT fk_fe866410dc2902e0 FOREIGN KEY (client_id_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT fk_fe8664107dd3081b FOREIGN KEY (detail_facture_id_id) REFERENCES detail_facture (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_fe8664107dd3081b ON facture (detail_facture_id_id)');
        $this->addSql('CREATE INDEX idx_fe866410dc2902e0 ON facture (client_id_id)');
        $this->addSql('CREATE INDEX idx_fe866410dac5be2b ON facture (entreprise_id_id)');
    }
}
