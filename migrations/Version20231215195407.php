<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231215195407 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entreprise ADD user_uuid UUID NOT NULL');
        $this->addSql('COMMENT ON COLUMN entreprise.user_uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE entreprise ADD CONSTRAINT FK_D19FA60ABFE1C6F FOREIGN KEY (user_uuid) REFERENCES "user" (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D19FA60ABFE1C6F ON entreprise (user_uuid)');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT fk_8d93d649a4aeafea');
        $this->addSql('DROP INDEX idx_8d93d649a4aeafea');
        $this->addSql('ALTER TABLE "user" DROP entreprise_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" ADD entreprise_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT fk_8d93d649a4aeafea FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_8d93d649a4aeafea ON "user" (entreprise_id)');
        $this->addSql('ALTER TABLE entreprise DROP CONSTRAINT FK_D19FA60ABFE1C6F');
        $this->addSql('DROP INDEX IDX_D19FA60ABFE1C6F');
        $this->addSql('ALTER TABLE entreprise DROP user_uuid');
    }
}
