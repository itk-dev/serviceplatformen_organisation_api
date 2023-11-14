<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231114092921 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE organisation_enhed_registrering_opgave (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', organisation_enhed_registrering_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', virkning_fra_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_fra_graense_indikator TINYINT(1) DEFAULT NULL, virkning_til_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_til_graense_indikator TINYINT(1) DEFAULT NULL, virkning_aktoer_ref_uuididentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_ref_urnidentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_type_kode VARCHAR(255) DEFAULT NULL, virkning_note_tekst VARCHAR(255) DEFAULT NULL, reference_id_uuididentifikator VARCHAR(255) DEFAULT NULL, reference_id_urnidentifikator VARCHAR(255) DEFAULT NULL, rolle_uuididentifikator VARCHAR(255) DEFAULT NULL, rolle_urnidentifikator VARCHAR(255) DEFAULT NULL, rolle_label VARCHAR(255) DEFAULT NULL, type_uuididentifikator VARCHAR(255) DEFAULT NULL, type_urnidentifikator VARCHAR(255) DEFAULT NULL, type_label VARCHAR(255) DEFAULT NULL, indeks VARCHAR(255) DEFAULT NULL, INDEX IDX_286AA33F5CBD4A93 (organisation_enhed_registrering_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE organisation_enhed_registrering_opgave ADD CONSTRAINT FK_286AA33F5CBD4A93 FOREIGN KEY (organisation_enhed_registrering_id) REFERENCES organisation_enhed_registrering (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE organisation_enhed_registrering_opgave DROP FOREIGN KEY FK_286AA33F5CBD4A93');
        $this->addSql('DROP TABLE organisation_enhed_registrering_opgave');
    }
}
