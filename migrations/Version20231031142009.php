<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231031142009 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE organisation_funktion (id VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisation_funktion_registrering (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', organisation_funktion_id VARCHAR(255) NOT NULL, funktionstype_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', note_tekst VARCHAR(255) DEFAULT NULL, tidspunkt VARCHAR(255) DEFAULT NULL, livscyklus_kode VARCHAR(255) DEFAULT NULL, bruger_ref_uuididentifikator VARCHAR(255) DEFAULT NULL, bruger_ref_urnidentifikator VARCHAR(255) DEFAULT NULL, INDEX IDX_E1792A317CBE2F22 (organisation_funktion_id), UNIQUE INDEX UNIQ_E1792A31DFABA626 (funktionstype_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisation_funktion_registrering_egenskab (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', organisation_funktion_registrering_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', funktion_navn VARCHAR(255) DEFAULT NULL, virkning_fra_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_fra_graense_indikator TINYINT(1) DEFAULT NULL, virkning_til_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_til_graense_indikator TINYINT(1) DEFAULT NULL, virkning_aktoer_ref_uuididentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_ref_urnidentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_type_kode VARCHAR(255) DEFAULT NULL, virkning_note_tekst VARCHAR(255) DEFAULT NULL, brugervendt_noegle_tekst VARCHAR(255) DEFAULT NULL, INDEX IDX_F485978955A4FC6 (organisation_funktion_registrering_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisation_funktion_registrering_funktionstype (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', virkning_fra_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_fra_graense_indikator TINYINT(1) DEFAULT NULL, virkning_til_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_til_graense_indikator TINYINT(1) DEFAULT NULL, virkning_aktoer_ref_uuididentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_ref_urnidentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_type_kode VARCHAR(255) DEFAULT NULL, virkning_note_tekst VARCHAR(255) DEFAULT NULL, reference_id_uuididentifikator VARCHAR(255) DEFAULT NULL, reference_id_urnidentifikator VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisation_funktion_registrering_gyldighed (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', organisation_funktion_registrering_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', virkning_fra_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_fra_graense_indikator TINYINT(1) DEFAULT NULL, virkning_til_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_til_graense_indikator TINYINT(1) DEFAULT NULL, virkning_aktoer_ref_uuididentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_ref_urnidentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_type_kode VARCHAR(255) DEFAULT NULL, virkning_note_tekst VARCHAR(255) DEFAULT NULL, gyldighed_status_kode VARCHAR(255) DEFAULT NULL, INDEX IDX_5F9F042655A4FC6 (organisation_funktion_registrering_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisation_funktion_registrering_tilknyttede_brugere (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', organisation_funktion_registrering_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', virkning_fra_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_fra_graense_indikator TINYINT(1) DEFAULT NULL, virkning_til_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_til_graense_indikator TINYINT(1) DEFAULT NULL, virkning_aktoer_ref_uuididentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_ref_urnidentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_type_kode VARCHAR(255) DEFAULT NULL, virkning_note_tekst VARCHAR(255) DEFAULT NULL, reference_id_uuididentifikator VARCHAR(255) DEFAULT NULL, reference_id_urnidentifikator VARCHAR(255) DEFAULT NULL, INDEX IDX_60D0A30B55A4FC6 (organisation_funktion_registrering_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisation_funktion_registrering_tilknyttede_enheder (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', organisation_funktion_registrering_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', virkning_fra_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_fra_graense_indikator TINYINT(1) DEFAULT NULL, virkning_til_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_til_graense_indikator TINYINT(1) DEFAULT NULL, virkning_aktoer_ref_uuididentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_ref_urnidentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_type_kode VARCHAR(255) DEFAULT NULL, virkning_note_tekst VARCHAR(255) DEFAULT NULL, reference_id_uuididentifikator VARCHAR(255) DEFAULT NULL, reference_id_urnidentifikator VARCHAR(255) DEFAULT NULL, INDEX IDX_6488AFBB55A4FC6 (organisation_funktion_registrering_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisation_funktion_registrering_tilknyttede_organisationer (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', organisation_funktion_registrering_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', virkning_fra_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_fra_graense_indikator TINYINT(1) DEFAULT NULL, virkning_til_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_til_graense_indikator TINYINT(1) DEFAULT NULL, virkning_aktoer_ref_uuididentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_ref_urnidentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_type_kode VARCHAR(255) DEFAULT NULL, virkning_note_tekst VARCHAR(255) DEFAULT NULL, reference_id_uuididentifikator VARCHAR(255) DEFAULT NULL, reference_id_urnidentifikator VARCHAR(255) DEFAULT NULL, INDEX IDX_CF20252555A4FC6 (organisation_funktion_registrering_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE organisation_funktion_registrering ADD CONSTRAINT FK_E1792A317CBE2F22 FOREIGN KEY (organisation_funktion_id) REFERENCES organisation_funktion (id)');
        $this->addSql('ALTER TABLE organisation_funktion_registrering ADD CONSTRAINT FK_E1792A31DFABA626 FOREIGN KEY (funktionstype_id) REFERENCES organisation_funktion_registrering_funktionstype (id)');
        $this->addSql('ALTER TABLE organisation_funktion_registrering_egenskab ADD CONSTRAINT FK_F485978955A4FC6 FOREIGN KEY (organisation_funktion_registrering_id) REFERENCES organisation_funktion_registrering (id)');
        $this->addSql('ALTER TABLE organisation_funktion_registrering_gyldighed ADD CONSTRAINT FK_5F9F042655A4FC6 FOREIGN KEY (organisation_funktion_registrering_id) REFERENCES organisation_funktion_registrering (id)');
        $this->addSql('ALTER TABLE organisation_funktion_registrering_tilknyttede_brugere ADD CONSTRAINT FK_60D0A30B55A4FC6 FOREIGN KEY (organisation_funktion_registrering_id) REFERENCES organisation_funktion_registrering (id)');
        $this->addSql('ALTER TABLE organisation_funktion_registrering_tilknyttede_enheder ADD CONSTRAINT FK_6488AFBB55A4FC6 FOREIGN KEY (organisation_funktion_registrering_id) REFERENCES organisation_funktion_registrering (id)');
        $this->addSql('ALTER TABLE organisation_funktion_registrering_tilknyttede_organisationer ADD CONSTRAINT FK_CF20252555A4FC6 FOREIGN KEY (organisation_funktion_registrering_id) REFERENCES organisation_funktion_registrering (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE organisation_funktion_registrering DROP FOREIGN KEY FK_E1792A317CBE2F22');
        $this->addSql('ALTER TABLE organisation_funktion_registrering DROP FOREIGN KEY FK_E1792A31DFABA626');
        $this->addSql('ALTER TABLE organisation_funktion_registrering_egenskab DROP FOREIGN KEY FK_F485978955A4FC6');
        $this->addSql('ALTER TABLE organisation_funktion_registrering_gyldighed DROP FOREIGN KEY FK_5F9F042655A4FC6');
        $this->addSql('ALTER TABLE organisation_funktion_registrering_tilknyttede_brugere DROP FOREIGN KEY FK_60D0A30B55A4FC6');
        $this->addSql('ALTER TABLE organisation_funktion_registrering_tilknyttede_enheder DROP FOREIGN KEY FK_6488AFBB55A4FC6');
        $this->addSql('ALTER TABLE organisation_funktion_registrering_tilknyttede_organisationer DROP FOREIGN KEY FK_CF20252555A4FC6');
        $this->addSql('DROP TABLE organisation_funktion');
        $this->addSql('DROP TABLE organisation_funktion_registrering');
        $this->addSql('DROP TABLE organisation_funktion_registrering_egenskab');
        $this->addSql('DROP TABLE organisation_funktion_registrering_funktionstype');
        $this->addSql('DROP TABLE organisation_funktion_registrering_gyldighed');
        $this->addSql('DROP TABLE organisation_funktion_registrering_tilknyttede_brugere');
        $this->addSql('DROP TABLE organisation_funktion_registrering_tilknyttede_enheder');
        $this->addSql('DROP TABLE organisation_funktion_registrering_tilknyttede_organisationer');
    }
}
