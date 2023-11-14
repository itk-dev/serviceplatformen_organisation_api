<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231031151405 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE organisation_enhed (id VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisation_enhed_registrering (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', organisation_enhed_id VARCHAR(255) NOT NULL, enhedstype_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', overordnet_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', tilhoerer_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', note_tekst VARCHAR(255) DEFAULT NULL, tidspunkt VARCHAR(255) DEFAULT NULL, livscyklus_kode VARCHAR(255) DEFAULT NULL, bruger_ref_uuididentifikator VARCHAR(255) DEFAULT NULL, bruger_ref_urnidentifikator VARCHAR(255) DEFAULT NULL, INDEX IDX_9770DD7E27FB9ABF (organisation_enhed_id), UNIQUE INDEX UNIQ_9770DD7E7B623DA6 (enhedstype_id), UNIQUE INDEX UNIQ_9770DD7E32980FB2 (overordnet_id), UNIQUE INDEX UNIQ_9770DD7EC42BE28C (tilhoerer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisation_enhed_registrering_adresser (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', organisation_enhed_registrering_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', virkning_fra_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_fra_graense_indikator TINYINT(1) DEFAULT NULL, virkning_til_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_til_graense_indikator TINYINT(1) DEFAULT NULL, virkning_aktoer_ref_uuididentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_ref_urnidentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_type_kode VARCHAR(255) DEFAULT NULL, virkning_note_tekst VARCHAR(255) DEFAULT NULL, reference_id_uuididentifikator VARCHAR(255) DEFAULT NULL, reference_id_urnidentifikator VARCHAR(255) DEFAULT NULL, rolle_uuididentifikator VARCHAR(255) DEFAULT NULL, rolle_urnidentifikator VARCHAR(255) DEFAULT NULL, rolle_label VARCHAR(255) DEFAULT NULL, type_uuididentifikator VARCHAR(255) DEFAULT NULL, type_urnidentifikator VARCHAR(255) DEFAULT NULL, type_label VARCHAR(255) DEFAULT NULL, indeks VARCHAR(255) DEFAULT NULL, INDEX IDX_FFACDDFE5CBD4A93 (organisation_enhed_registrering_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisation_enhed_registrering_egenskab (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', organisation_enhed_registrering_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', enhed_navn VARCHAR(255) DEFAULT NULL, virkning_fra_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_fra_graense_indikator TINYINT(1) DEFAULT NULL, virkning_til_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_til_graense_indikator TINYINT(1) DEFAULT NULL, virkning_aktoer_ref_uuididentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_ref_urnidentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_type_kode VARCHAR(255) DEFAULT NULL, virkning_note_tekst VARCHAR(255) DEFAULT NULL, brugervendt_noegle_tekst VARCHAR(255) DEFAULT NULL, INDEX IDX_48E3ED035CBD4A93 (organisation_enhed_registrering_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisation_enhed_registrering_enhedstype (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', virkning_fra_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_fra_graense_indikator TINYINT(1) DEFAULT NULL, virkning_til_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_til_graense_indikator TINYINT(1) DEFAULT NULL, virkning_aktoer_ref_uuididentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_ref_urnidentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_type_kode VARCHAR(255) DEFAULT NULL, virkning_note_tekst VARCHAR(255) DEFAULT NULL, reference_id_uuididentifikator VARCHAR(255) DEFAULT NULL, reference_id_urnidentifikator VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisation_enhed_registrering_gyldighed (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', organisation_enhed_registrering_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', virkning_fra_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_fra_graense_indikator TINYINT(1) DEFAULT NULL, virkning_til_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_til_graense_indikator TINYINT(1) DEFAULT NULL, virkning_aktoer_ref_uuididentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_ref_urnidentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_type_kode VARCHAR(255) DEFAULT NULL, virkning_note_tekst VARCHAR(255) DEFAULT NULL, gyldighed_status_kode VARCHAR(255) DEFAULT NULL, INDEX IDX_524E08625CBD4A93 (organisation_enhed_registrering_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisation_enhed_registrering_overordnet (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', virkning_fra_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_fra_graense_indikator TINYINT(1) DEFAULT NULL, virkning_til_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_til_graense_indikator TINYINT(1) DEFAULT NULL, virkning_aktoer_ref_uuididentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_ref_urnidentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_type_kode VARCHAR(255) DEFAULT NULL, virkning_note_tekst VARCHAR(255) DEFAULT NULL, reference_id_uuididentifikator VARCHAR(255) DEFAULT NULL, reference_id_urnidentifikator VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisation_enhed_registrering_tilhoerer (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', virkning_fra_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_fra_graense_indikator TINYINT(1) DEFAULT NULL, virkning_til_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_til_graense_indikator TINYINT(1) DEFAULT NULL, virkning_aktoer_ref_uuididentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_ref_urnidentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_type_kode VARCHAR(255) DEFAULT NULL, virkning_note_tekst VARCHAR(255) DEFAULT NULL, reference_id_uuididentifikator VARCHAR(255) DEFAULT NULL, reference_id_urnidentifikator VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE organisation_enhed_registrering ADD CONSTRAINT FK_9770DD7E27FB9ABF FOREIGN KEY (organisation_enhed_id) REFERENCES organisation_enhed (id)');
        $this->addSql('ALTER TABLE organisation_enhed_registrering ADD CONSTRAINT FK_9770DD7E7B623DA6 FOREIGN KEY (enhedstype_id) REFERENCES organisation_enhed_registrering_enhedstype (id)');
        $this->addSql('ALTER TABLE organisation_enhed_registrering ADD CONSTRAINT FK_9770DD7E32980FB2 FOREIGN KEY (overordnet_id) REFERENCES organisation_enhed_registrering_overordnet (id)');
        $this->addSql('ALTER TABLE organisation_enhed_registrering ADD CONSTRAINT FK_9770DD7EC42BE28C FOREIGN KEY (tilhoerer_id) REFERENCES organisation_enhed_registrering_tilhoerer (id)');
        $this->addSql('ALTER TABLE organisation_enhed_registrering_adresser ADD CONSTRAINT FK_FFACDDFE5CBD4A93 FOREIGN KEY (organisation_enhed_registrering_id) REFERENCES organisation_enhed_registrering (id)');
        $this->addSql('ALTER TABLE organisation_enhed_registrering_egenskab ADD CONSTRAINT FK_48E3ED035CBD4A93 FOREIGN KEY (organisation_enhed_registrering_id) REFERENCES organisation_enhed_registrering (id)');
        $this->addSql('ALTER TABLE organisation_enhed_registrering_gyldighed ADD CONSTRAINT FK_524E08625CBD4A93 FOREIGN KEY (organisation_enhed_registrering_id) REFERENCES organisation_enhed_registrering (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE organisation_enhed_registrering DROP FOREIGN KEY FK_9770DD7E27FB9ABF');
        $this->addSql('ALTER TABLE organisation_enhed_registrering DROP FOREIGN KEY FK_9770DD7E7B623DA6');
        $this->addSql('ALTER TABLE organisation_enhed_registrering DROP FOREIGN KEY FK_9770DD7E32980FB2');
        $this->addSql('ALTER TABLE organisation_enhed_registrering DROP FOREIGN KEY FK_9770DD7EC42BE28C');
        $this->addSql('ALTER TABLE organisation_enhed_registrering_adresser DROP FOREIGN KEY FK_FFACDDFE5CBD4A93');
        $this->addSql('ALTER TABLE organisation_enhed_registrering_egenskab DROP FOREIGN KEY FK_48E3ED035CBD4A93');
        $this->addSql('ALTER TABLE organisation_enhed_registrering_gyldighed DROP FOREIGN KEY FK_524E08625CBD4A93');
        $this->addSql('DROP TABLE organisation_enhed');
        $this->addSql('DROP TABLE organisation_enhed_registrering');
        $this->addSql('DROP TABLE organisation_enhed_registrering_adresser');
        $this->addSql('DROP TABLE organisation_enhed_registrering_egenskab');
        $this->addSql('DROP TABLE organisation_enhed_registrering_enhedstype');
        $this->addSql('DROP TABLE organisation_enhed_registrering_gyldighed');
        $this->addSql('DROP TABLE organisation_enhed_registrering_overordnet');
        $this->addSql('DROP TABLE organisation_enhed_registrering_tilhoerer');
    }
}
