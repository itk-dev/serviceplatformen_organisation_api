<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231123142059 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE organisation_enhed_registrering DROP FOREIGN KEY FK_9770DD7E27FB9ABF');
        $this->addSql('DROP TABLE organisation_enhed');
        $this->addSql('DROP INDEX IDX_9770DD7E27FB9ABF ON organisation_enhed_registrering');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE organisation_enhed (id VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE organisation_enhed_registrering ADD CONSTRAINT FK_9770DD7E27FB9ABF FOREIGN KEY (organisation_enhed_id) REFERENCES organisation_enhed (id)');
        $this->addSql('CREATE INDEX IDX_9770DD7E27FB9ABF ON organisation_enhed_registrering (organisation_enhed_id)');
    }
}
