<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231031083223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE person (id VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_registrering (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', person_id VARCHAR(255) NOT NULL, note_tekst VARCHAR(255) DEFAULT NULL, tidspunkt VARCHAR(255) DEFAULT NULL, livscyklus_kode VARCHAR(255) DEFAULT NULL, bruger_ref_uuididentifikator VARCHAR(255) DEFAULT NULL, bruger_ref_urnidentifikator VARCHAR(255) DEFAULT NULL, INDEX IDX_318A3713217BBB47 (person_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_registrering_egenskab (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', person_registrering_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', brugervendt_noegle_tekst VARCHAR(255) NOT NULL, cpr_nummer_tekst VARCHAR(255) DEFAULT NULL, navn_tekst VARCHAR(255) NOT NULL, virkning_fra_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_fra_graense_indikator TINYINT(1) DEFAULT NULL, virkning_til_tidsstempel_dato_tid VARCHAR(255) DEFAULT NULL, virkning_til_graense_indikator TINYINT(1) DEFAULT NULL, virkning_aktoer_ref_uuididentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_ref_urnidentifikator VARCHAR(255) DEFAULT NULL, virkning_aktoer_type_kode VARCHAR(255) DEFAULT NULL, virkning_note_tekst VARCHAR(255) DEFAULT NULL, INDEX IDX_F894253DE54B1222 (person_registrering_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE person_registrering ADD CONSTRAINT FK_318A3713217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE person_registrering_egenskab ADD CONSTRAINT FK_F894253DE54B1222 FOREIGN KEY (person_registrering_id) REFERENCES person_registrering (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person_registrering DROP FOREIGN KEY FK_318A3713217BBB47');
        $this->addSql('ALTER TABLE person_registrering_egenskab DROP FOREIGN KEY FK_F894253DE54B1222');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE person_registrering');
        $this->addSql('DROP TABLE person_registrering_egenskab');
    }
}
