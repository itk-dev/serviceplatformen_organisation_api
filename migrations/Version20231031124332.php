<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231031124332 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adresse (id VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE adresse_registrering (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', adresse_id VARCHAR(255) NOT NULL, note_tekst VARCHAR(255) DEFAULT NULL, tidspunkt VARCHAR(255) DEFAULT NULL, livscyklus_kode VARCHAR(255) DEFAULT NULL, bruger_ref_uuididentifikator VARCHAR(255) DEFAULT NULL, bruger_ref_urnidentifikator VARCHAR(255) DEFAULT NULL, INDEX IDX_E2FF264E4DE7DC5C (adresse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE adresse_registrering_egenskab (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', adresse_registrering_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', adresse_tekst VARCHAR(255) DEFAULT NULL, virkning_fra_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_fra_graense_indikator TINYINT(1) DEFAULT NULL, virkning_til_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_til_graense_indikator TINYINT(1) DEFAULT NULL, virkning_aktoer_ref_uuididentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_ref_urnidentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_type_kode VARCHAR(255) DEFAULT NULL, virkning_note_tekst VARCHAR(255) DEFAULT NULL, brugervendt_noegle_tekst VARCHAR(255) NOT NULL, INDEX IDX_E16223A4F29909B1 (adresse_registrering_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adresse_registrering ADD CONSTRAINT FK_E2FF264E4DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adresse (id)');
        $this->addSql('ALTER TABLE adresse_registrering_egenskab ADD CONSTRAINT FK_E16223A4F29909B1 FOREIGN KEY (adresse_registrering_id) REFERENCES adresse_registrering (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adresse_registrering DROP FOREIGN KEY FK_E2FF264E4DE7DC5C');
        $this->addSql('ALTER TABLE adresse_registrering_egenskab DROP FOREIGN KEY FK_E16223A4F29909B1');
        $this->addSql('DROP TABLE adresse');
        $this->addSql('DROP TABLE adresse_registrering');
        $this->addSql('DROP TABLE adresse_registrering_egenskab');
    }
}
