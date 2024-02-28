<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240226212828 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE detail_devis DROP CONSTRAINT fk_f6d70e728b27c52b');
        $this->addSql('DROP INDEX uniq_f6d70e728b27c52b');
        $this->addSql('ALTER TABLE detail_devis RENAME COLUMN devis TO devis_id');
        $this->addSql('ALTER TABLE detail_devis ADD CONSTRAINT FK_F6D70E7241DEFADA FOREIGN KEY (devis_id) REFERENCES devis (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_F6D70E7241DEFADA ON detail_devis (devis_id)');
        $this->addSql('ALTER TABLE devis DROP CONSTRAINT fk_8b27c52b21575dbf');
        $this->addSql('DROP INDEX uniq_8b27c52b21575dbf');
        $this->addSql('ALTER TABLE devis DROP detaildevis');
        $this->addSql('ALTER TABLE produit DROP CONSTRAINT fk_29a5ec278b27c52b');
        $this->addSql('DROP INDEX idx_29a5ec278b27c52b');
        $this->addSql('ALTER TABLE produit DROP devis');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE detail_devis DROP CONSTRAINT FK_F6D70E7241DEFADA');
        $this->addSql('DROP INDEX IDX_F6D70E7241DEFADA');
        $this->addSql('ALTER TABLE detail_devis RENAME COLUMN devis_id TO devis');
        $this->addSql('ALTER TABLE detail_devis ADD CONSTRAINT fk_f6d70e728b27c52b FOREIGN KEY (devis) REFERENCES devis (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_f6d70e728b27c52b ON detail_devis (devis)');
        $this->addSql('ALTER TABLE devis ADD detaildevis INT DEFAULT NULL');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT fk_8b27c52b21575dbf FOREIGN KEY (detaildevis) REFERENCES detail_devis (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_8b27c52b21575dbf ON devis (detaildevis)');
        $this->addSql('ALTER TABLE produit ADD devis INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT fk_29a5ec278b27c52b FOREIGN KEY (devis) REFERENCES devis (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_29a5ec278b27c52b ON produit (devis)');
    }
}
