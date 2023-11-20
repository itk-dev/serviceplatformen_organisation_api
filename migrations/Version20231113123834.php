<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231113123834 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            "CREATE OR REPLACE VIEW bruger_data AS
                SELECT
                    bruger_registrering.bruger_id AS id,
                    bruger_registrering_egenskab.bruger_navn AS az,
                    person_registrering_egenskab.navn_tekst AS navn,
                    adresse_registrering_egenskab_email.adresse_tekst AS email,
                    adresse_registrering_egenskab_telefon.adresse_tekst AS telefon,
                    adresse_registrering_egenskab_lokation.adresse_tekst AS lokation
                FROM bruger_registrering
                JOIN bruger_registrering_egenskab ON bruger_registrering.id = bruger_registrering_egenskab.bruger_registrering_id
                JOIN bruger_registrering_tilknyttede_personer ON bruger_registrering.id = bruger_registrering_tilknyttede_personer.bruger_registrering_id
                JOIN person_registrering ON bruger_registrering_tilknyttede_personer.reference_id_uuididentifikator = person_registrering.person_id
                JOIN person_registrering_egenskab ON person_registrering.id = person_registrering_egenskab.person_registrering_id
                
                LEFT OUTER JOIN bruger_registrering_adresse AS bruger_registrering_adresse_email ON bruger_registrering.id = bruger_registrering_adresse_email.bruger_registrering_id
                    AND bruger_registrering_adresse_email.rolle_label = 'Email_bruger'
                LEFT OUTER JOIN adresse_registrering AS adresse_registrering_email ON bruger_registrering_adresse_email.reference_id_uuididentifikator = adresse_registrering_email.adresse_id
                LEFT OUTER JOIN adresse_registrering_egenskab AS adresse_registrering_egenskab_email ON adresse_registrering_email.id = adresse_registrering_egenskab_email.adresse_registrering_id
                
                LEFT OUTER JOIN bruger_registrering_adresse AS bruger_registrering_adresse_telefon ON bruger_registrering.id = bruger_registrering_adresse_telefon.bruger_registrering_id
                    AND bruger_registrering_adresse_telefon.rolle_label = 'Mobiltelefon_bruger'
                LEFT OUTER JOIN adresse_registrering AS adresse_registrering_telefon ON bruger_registrering_adresse_telefon.reference_id_uuididentifikator = adresse_registrering_telefon.adresse_id
                LEFT OUTER JOIN adresse_registrering_egenskab AS adresse_registrering_egenskab_telefon ON adresse_registrering_telefon.id = adresse_registrering_egenskab_telefon.adresse_registrering_id
                
                LEFT OUTER JOIN bruger_registrering_adresse AS bruger_registrering_adresse_lokation ON bruger_registrering.id = bruger_registrering_adresse_lokation.bruger_registrering_id
                    AND bruger_registrering_adresse_lokation.rolle_label = 'Lokation_bruger'
                LEFT OUTER JOIN adresse_registrering AS adresse_registrering_lokation ON bruger_registrering_adresse_lokation.reference_id_uuididentifikator = adresse_registrering_lokation.adresse_id
                LEFT OUTER JOIN adresse_registrering_egenskab AS adresse_registrering_egenskab_lokation ON adresse_registrering_lokation.id = adresse_registrering_egenskab_lokation.adresse_registrering_id
            ;"
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP VIEW bruger_data');
    }
}
