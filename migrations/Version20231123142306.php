<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231123142306 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bruger_registrering DROP FOREIGN KEY FK_A03D8291D8AC6707');
        $this->addSql('DROP TABLE bruger');
        $this->addSql('DROP INDEX IDX_A03D8291D8AC6707 ON bruger_registrering');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bruger (id VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE bruger_registrering ADD CONSTRAINT FK_A03D8291D8AC6707 FOREIGN KEY (bruger_id) REFERENCES bruger (id)');
        $this->addSql('CREATE INDEX IDX_A03D8291D8AC6707 ON bruger_registrering (bruger_id)');
    }
}
