<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231123141704 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE organisation_funktion_registrering DROP FOREIGN KEY FK_E1792A317CBE2F22');
        $this->addSql('DROP TABLE organisation_funktion');
        $this->addSql('DROP INDEX IDX_E1792A317CBE2F22 ON organisation_funktion_registrering');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE organisation_funktion (id VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE organisation_funktion_registrering ADD CONSTRAINT FK_E1792A317CBE2F22 FOREIGN KEY (organisation_funktion_id) REFERENCES organisation_funktion (id)');
        $this->addSql('CREATE INDEX IDX_E1792A317CBE2F22 ON organisation_funktion_registrering (organisation_funktion_id)');
    }
}
