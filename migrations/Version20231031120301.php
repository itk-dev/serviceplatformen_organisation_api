<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231031120301 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bruger (id VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bruger_registrering (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', bruger_id VARCHAR(255) NOT NULL, note_tekst VARCHAR(255) DEFAULT NULL, tidspunkt VARCHAR(255) DEFAULT NULL, livscyklus_kode VARCHAR(255) DEFAULT NULL, bruger_ref_uuididentifikator VARCHAR(255) DEFAULT NULL, bruger_ref_urnidentifikator VARCHAR(255) DEFAULT NULL, INDEX IDX_A03D8291D8AC6707 (bruger_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bruger_registrering_adresse (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', bruger_registrering_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', indeks VARCHAR(255) DEFAULT NULL, virkning_fra_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_fra_graense_indikator TINYINT(1) DEFAULT NULL, virkning_til_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_til_graense_indikator TINYINT(1) DEFAULT NULL, virkning_aktoer_ref_uuididentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_ref_urnidentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_type_kode VARCHAR(255) DEFAULT NULL, virkning_note_tekst VARCHAR(255) DEFAULT NULL, reference_id_uuididentifikator VARCHAR(255) DEFAULT NULL, reference_id_urnidentifikator VARCHAR(255) DEFAULT NULL, rolle_uuididentifikator VARCHAR(255) DEFAULT NULL, rolle_urnidentifikator VARCHAR(255) DEFAULT NULL, rolle_label VARCHAR(255) DEFAULT NULL, type_uuididentifikator VARCHAR(255) DEFAULT NULL, type_urnidentifikator VARCHAR(255) DEFAULT NULL, type_label VARCHAR(255) DEFAULT NULL, INDEX IDX_83772D3ACB4DA18F (bruger_registrering_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bruger_registrering_egenskab (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', bruger_registrering_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', brugervendt_noegle_tekst VARCHAR(255) DEFAULT NULL, bruger_navn VARCHAR(255) DEFAULT NULL, bruger_type_tekst VARCHAR(255) DEFAULT NULL, virkning_fra_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_fra_graense_indikator TINYINT(1) DEFAULT NULL, virkning_til_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_til_graense_indikator TINYINT(1) DEFAULT NULL, virkning_aktoer_ref_uuididentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_ref_urnidentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_type_kode VARCHAR(255) DEFAULT NULL, virkning_note_tekst VARCHAR(255) DEFAULT NULL, INDEX IDX_1DC961FFCB4DA18F (bruger_registrering_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bruger_registrering_gyldighed (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', bruger_registrering_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', gyldighed_status_kode VARCHAR(255) DEFAULT NULL, virkning_fra_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_fra_graense_indikator TINYINT(1) DEFAULT NULL, virkning_til_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_til_graense_indikator TINYINT(1) DEFAULT NULL, virkning_aktoer_ref_uuididentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_ref_urnidentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_type_kode VARCHAR(255) DEFAULT NULL, virkning_note_tekst VARCHAR(255) DEFAULT NULL, INDEX IDX_E6109CD9CB4DA18F (bruger_registrering_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bruger_registrering_tilhoerer (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', bruger_registrering_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', virkning_fra_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_fra_graense_indikator TINYINT(1) DEFAULT NULL, virkning_til_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_til_graense_indikator TINYINT(1) DEFAULT NULL, virkning_aktoer_ref_uuididentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_ref_urnidentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_type_kode VARCHAR(255) DEFAULT NULL, virkning_note_tekst VARCHAR(255) DEFAULT NULL, reference_id_uuididentifikator VARCHAR(255) DEFAULT NULL, reference_id_urnidentifikator VARCHAR(255) DEFAULT NULL, INDEX IDX_B808BDE1CB4DA18F (bruger_registrering_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bruger_registrering_tilknyttede_personer (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', bruger_registrering_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', virkning_fra_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_fra_graense_indikator TINYINT(1) DEFAULT NULL, virkning_til_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_til_graense_indikator TINYINT(1) DEFAULT NULL, virkning_aktoer_ref_uuididentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_ref_urnidentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_type_kode VARCHAR(255) DEFAULT NULL, virkning_note_tekst VARCHAR(255) DEFAULT NULL, reference_id_uuididentifikator VARCHAR(255) DEFAULT NULL, reference_id_urnidentifikator VARCHAR(255) DEFAULT NULL, INDEX IDX_2DAA808FCB4DA18F (bruger_registrering_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bruger_registrering ADD CONSTRAINT FK_A03D8291D8AC6707 FOREIGN KEY (bruger_id) REFERENCES bruger (id)');
        $this->addSql('ALTER TABLE bruger_registrering_adresse ADD CONSTRAINT FK_83772D3ACB4DA18F FOREIGN KEY (bruger_registrering_id) REFERENCES bruger_registrering (id)');
        $this->addSql('ALTER TABLE bruger_registrering_egenskab ADD CONSTRAINT FK_1DC961FFCB4DA18F FOREIGN KEY (bruger_registrering_id) REFERENCES bruger_registrering (id)');
        $this->addSql('ALTER TABLE bruger_registrering_gyldighed ADD CONSTRAINT FK_E6109CD9CB4DA18F FOREIGN KEY (bruger_registrering_id) REFERENCES bruger_registrering (id)');
        $this->addSql('ALTER TABLE bruger_registrering_tilhoerer ADD CONSTRAINT FK_B808BDE1CB4DA18F FOREIGN KEY (bruger_registrering_id) REFERENCES bruger_registrering (id)');
        $this->addSql('ALTER TABLE bruger_registrering_tilknyttede_personer ADD CONSTRAINT FK_2DAA808FCB4DA18F FOREIGN KEY (bruger_registrering_id) REFERENCES bruger_registrering (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bruger_registrering DROP FOREIGN KEY FK_A03D8291D8AC6707');
        $this->addSql('ALTER TABLE bruger_registrering_adresse DROP FOREIGN KEY FK_83772D3ACB4DA18F');
        $this->addSql('ALTER TABLE bruger_registrering_egenskab DROP FOREIGN KEY FK_1DC961FFCB4DA18F');
        $this->addSql('ALTER TABLE bruger_registrering_gyldighed DROP FOREIGN KEY FK_E6109CD9CB4DA18F');
        $this->addSql('ALTER TABLE bruger_registrering_tilhoerer DROP FOREIGN KEY FK_B808BDE1CB4DA18F');
        $this->addSql('ALTER TABLE bruger_registrering_tilknyttede_personer DROP FOREIGN KEY FK_2DAA808FCB4DA18F');
        $this->addSql('DROP TABLE bruger');
        $this->addSql('DROP TABLE bruger_registrering');
        $this->addSql('DROP TABLE bruger_registrering_adresse');
        $this->addSql('DROP TABLE bruger_registrering_egenskab');
        $this->addSql('DROP TABLE bruger_registrering_gyldighed');
        $this->addSql('DROP TABLE bruger_registrering_tilhoerer');
        $this->addSql('DROP TABLE bruger_registrering_tilknyttede_personer');
    }
}
