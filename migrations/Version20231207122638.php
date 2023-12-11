<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231207122638 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adresse_registrering (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', adresse_id VARCHAR(255) NOT NULL, INDEX adresse_id_idx (adresse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE adresse_registrering_egenskab (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', adresse_registrering_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', adresse_tekst VARCHAR(255) DEFAULT NULL, INDEX IDX_E16223A4F29909B1 (adresse_registrering_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bruger_registrering (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', bruger_id VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bruger_registrering_adresse (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', bruger_registrering_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', reference_id_uuididentifikator VARCHAR(255) DEFAULT NULL, rolle_label VARCHAR(255) DEFAULT NULL, INDEX IDX_83772D3ACB4DA18F (bruger_registrering_id), INDEX reference_id_uuididentifikator_idx (reference_id_uuididentifikator), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bruger_registrering_egenskab (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', bruger_registrering_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', bruger_navn VARCHAR(255) DEFAULT NULL, bruger_type_tekst VARCHAR(255) DEFAULT NULL, INDEX IDX_1DC961FFCB4DA18F (bruger_registrering_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bruger_registrering_tilknyttede_personer (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', bruger_registrering_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', reference_id_uuididentifikator VARCHAR(255) DEFAULT NULL, INDEX IDX_2DAA808FCB4DA18F (bruger_registrering_id), INDEX reference_id_uuididentifikator_idx (reference_id_uuididentifikator), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisation_enhed_registrering (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', overordnet_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', organisation_enhed_id VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_9770DD7E32980FB2 (overordnet_id), INDEX organisation_enhed_id_idx (organisation_enhed_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisation_enhed_registrering_adresser (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', organisation_enhed_registrering_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', reference_id_uuididentifikator VARCHAR(255) DEFAULT NULL, rolle_label VARCHAR(255) DEFAULT NULL, INDEX IDX_FFACDDFE5CBD4A93 (organisation_enhed_registrering_id), INDEX reference_id_uuididentifikator_idx (reference_id_uuididentifikator), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisation_enhed_registrering_egenskab (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', organisation_enhed_registrering_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', enhed_navn VARCHAR(255) DEFAULT NULL, INDEX IDX_48E3ED035CBD4A93 (organisation_enhed_registrering_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisation_enhed_registrering_overordnet (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', reference_id_uuididentifikator VARCHAR(255) DEFAULT NULL, INDEX reference_id_uuididentifikator_idx (reference_id_uuididentifikator), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisation_funktion_registrering (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', funktionstype_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', organisation_funktion_id VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_E1792A31DFABA626 (funktionstype_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisation_funktion_registrering_egenskab (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', organisation_funktion_registrering_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', funktion_navn VARCHAR(255) DEFAULT NULL, INDEX IDX_F485978955A4FC6 (organisation_funktion_registrering_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisation_funktion_registrering_funktionstype (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', reference_id_uuididentifikator VARCHAR(255) DEFAULT NULL, INDEX reference_id_uuididentifikator_idx (reference_id_uuididentifikator), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisation_funktion_registrering_tilknyttede_brugere (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', organisation_funktion_registrering_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', reference_id_uuididentifikator VARCHAR(255) DEFAULT NULL, INDEX IDX_60D0A30B55A4FC6 (organisation_funktion_registrering_id), INDEX reference_id_uuididentifikator_idx (reference_id_uuididentifikator), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisation_funktion_registrering_tilknyttede_enheder (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', organisation_funktion_registrering_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', reference_id_uuididentifikator VARCHAR(255) DEFAULT NULL, INDEX IDX_6488AFBB55A4FC6 (organisation_funktion_registrering_id), INDEX reference_id_uuididentifikator_idx (reference_id_uuididentifikator), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_registrering (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', person_id VARCHAR(255) NOT NULL, INDEX person_id_idx (person_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_registrering_egenskab (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', person_registrering_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', cpr_nummer_tekst VARCHAR(255) DEFAULT NULL, navn_tekst VARCHAR(255) NOT NULL, INDEX IDX_F894253DE54B1222 (person_registrering_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adresse_registrering_egenskab ADD CONSTRAINT FK_E16223A4F29909B1 FOREIGN KEY (adresse_registrering_id) REFERENCES adresse_registrering (id)');
        $this->addSql('ALTER TABLE bruger_registrering_adresse ADD CONSTRAINT FK_83772D3ACB4DA18F FOREIGN KEY (bruger_registrering_id) REFERENCES bruger_registrering (id)');
        $this->addSql('ALTER TABLE bruger_registrering_egenskab ADD CONSTRAINT FK_1DC961FFCB4DA18F FOREIGN KEY (bruger_registrering_id) REFERENCES bruger_registrering (id)');
        $this->addSql('ALTER TABLE bruger_registrering_tilknyttede_personer ADD CONSTRAINT FK_2DAA808FCB4DA18F FOREIGN KEY (bruger_registrering_id) REFERENCES bruger_registrering (id)');
        $this->addSql('ALTER TABLE organisation_enhed_registrering ADD CONSTRAINT FK_9770DD7E32980FB2 FOREIGN KEY (overordnet_id) REFERENCES organisation_enhed_registrering_overordnet (id)');
        $this->addSql('ALTER TABLE organisation_enhed_registrering_adresser ADD CONSTRAINT FK_FFACDDFE5CBD4A93 FOREIGN KEY (organisation_enhed_registrering_id) REFERENCES organisation_enhed_registrering (id)');
        $this->addSql('ALTER TABLE organisation_enhed_registrering_egenskab ADD CONSTRAINT FK_48E3ED035CBD4A93 FOREIGN KEY (organisation_enhed_registrering_id) REFERENCES organisation_enhed_registrering (id)');
        $this->addSql('ALTER TABLE organisation_funktion_registrering ADD CONSTRAINT FK_E1792A31DFABA626 FOREIGN KEY (funktionstype_id) REFERENCES organisation_funktion_registrering_funktionstype (id)');
        $this->addSql('ALTER TABLE organisation_funktion_registrering_egenskab ADD CONSTRAINT FK_F485978955A4FC6 FOREIGN KEY (organisation_funktion_registrering_id) REFERENCES organisation_funktion_registrering (id)');
        $this->addSql('ALTER TABLE organisation_funktion_registrering_tilknyttede_brugere ADD CONSTRAINT FK_60D0A30B55A4FC6 FOREIGN KEY (organisation_funktion_registrering_id) REFERENCES organisation_funktion_registrering (id)');
        $this->addSql('ALTER TABLE organisation_funktion_registrering_tilknyttede_enheder ADD CONSTRAINT FK_6488AFBB55A4FC6 FOREIGN KEY (organisation_funktion_registrering_id) REFERENCES organisation_funktion_registrering (id)');
        $this->addSql('ALTER TABLE person_registrering_egenskab ADD CONSTRAINT FK_F894253DE54B1222 FOREIGN KEY (person_registrering_id) REFERENCES person_registrering (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adresse_registrering_egenskab DROP FOREIGN KEY FK_E16223A4F29909B1');
        $this->addSql('ALTER TABLE bruger_registrering_adresse DROP FOREIGN KEY FK_83772D3ACB4DA18F');
        $this->addSql('ALTER TABLE bruger_registrering_egenskab DROP FOREIGN KEY FK_1DC961FFCB4DA18F');
        $this->addSql('ALTER TABLE bruger_registrering_tilknyttede_personer DROP FOREIGN KEY FK_2DAA808FCB4DA18F');
        $this->addSql('ALTER TABLE organisation_enhed_registrering DROP FOREIGN KEY FK_9770DD7E32980FB2');
        $this->addSql('ALTER TABLE organisation_enhed_registrering_adresser DROP FOREIGN KEY FK_FFACDDFE5CBD4A93');
        $this->addSql('ALTER TABLE organisation_enhed_registrering_egenskab DROP FOREIGN KEY FK_48E3ED035CBD4A93');
        $this->addSql('ALTER TABLE organisation_funktion_registrering DROP FOREIGN KEY FK_E1792A31DFABA626');
        $this->addSql('ALTER TABLE organisation_funktion_registrering_egenskab DROP FOREIGN KEY FK_F485978955A4FC6');
        $this->addSql('ALTER TABLE organisation_funktion_registrering_tilknyttede_brugere DROP FOREIGN KEY FK_60D0A30B55A4FC6');
        $this->addSql('ALTER TABLE organisation_funktion_registrering_tilknyttede_enheder DROP FOREIGN KEY FK_6488AFBB55A4FC6');
        $this->addSql('ALTER TABLE person_registrering_egenskab DROP FOREIGN KEY FK_F894253DE54B1222');
        $this->addSql('DROP TABLE adresse_registrering');
        $this->addSql('DROP TABLE adresse_registrering_egenskab');
        $this->addSql('DROP TABLE bruger_registrering');
        $this->addSql('DROP TABLE bruger_registrering_adresse');
        $this->addSql('DROP TABLE bruger_registrering_egenskab');
        $this->addSql('DROP TABLE bruger_registrering_tilknyttede_personer');
        $this->addSql('DROP TABLE organisation_enhed_registrering');
        $this->addSql('DROP TABLE organisation_enhed_registrering_adresser');
        $this->addSql('DROP TABLE organisation_enhed_registrering_egenskab');
        $this->addSql('DROP TABLE organisation_enhed_registrering_overordnet');
        $this->addSql('DROP TABLE organisation_funktion_registrering');
        $this->addSql('DROP TABLE organisation_funktion_registrering_egenskab');
        $this->addSql('DROP TABLE organisation_funktion_registrering_funktionstype');
        $this->addSql('DROP TABLE organisation_funktion_registrering_tilknyttede_brugere');
        $this->addSql('DROP TABLE organisation_funktion_registrering_tilknyttede_enheder');
        $this->addSql('DROP TABLE person_registrering');
        $this->addSql('DROP TABLE person_registrering_egenskab');
    }
}
