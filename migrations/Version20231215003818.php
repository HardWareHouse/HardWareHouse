<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231215003818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entreprise DROP CONSTRAINT fk_d19fa60abfe1c6f');
        $this->addSql('DROP INDEX idx_d19fa60abfe1c6f');
        $this->addSql('ALTER TABLE entreprise DROP user_uuid');
        $this->addSql('ALTER TABLE "user" ADD entreprise_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8D93D649A4AEAFEA ON "user" (entreprise_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE entreprise ADD user_uuid UUID NOT NULL');
        $this->addSql('COMMENT ON COLUMN entreprise.user_uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE entreprise ADD CONSTRAINT fk_d19fa60abfe1c6f FOREIGN KEY (user_uuid) REFERENCES "user" (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_d19fa60abfe1c6f ON entreprise (user_uuid)');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649A4AEAFEA');
        $this->addSql('DROP INDEX IDX_8D93D649A4AEAFEA');
        $this->addSql('ALTER TABLE "user" DROP entreprise_id');
    }
}
