<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231207122658 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE OR REPLACE VIEW funktion AS
    SELECT
        organisation_funktion_registrering.organisation_funktion_id AS id,
        organisation_funktion_registrering_tilknyttede_brugere.reference_id_uuididentifikator AS bruger_id,
        organisation_funktion_registrering_egenskab.funktion_navn AS funktionsnavn,
        organisation_enhed_registrering_egenskab.enhed_navn AS enhedsnavn,
        adresse_registrering_egenskab.adresse_tekst AS adresse,
        organisation_funktion_registrering_tilknyttede_enheder.reference_id_uuididentifikator AS tilknyttet_enhed_id,
        organisation_funktion_registrering_funktionstype.reference_id_uuididentifikator AS funktions_type
    FROM organisation_funktion_registrering

    -- Add funktionsnavn
    LEFT OUTER JOIN organisation_funktion_registrering_egenskab ON organisation_funktion_registrering.id = organisation_funktion_registrering_egenskab.organisation_funktion_registrering_id

    -- Add tilknyttet_enhed_id
    LEFT OUTER JOIN organisation_funktion_registrering_tilknyttede_enheder ON organisation_funktion_registrering.id = organisation_funktion_registrering_tilknyttede_enheder.organisation_funktion_registrering_id

    -- Add enhedsnavn
    LEFT OUTER JOIN organisation_enhed_registrering ON organisation_funktion_registrering_tilknyttede_enheder.reference_id_uuididentifikator = organisation_enhed_registrering.organisation_enhed_id
    LEFT OUTER JOIN organisation_enhed_registrering_egenskab ON organisation_enhed_registrering.id = organisation_enhed_registrering_egenskab.organisation_enhed_registrering_id

    -- Add bruger_id
    LEFT OUTER JOIN organisation_funktion_registrering_tilknyttede_brugere ON organisation_funktion_registrering.id = organisation_funktion_registrering_tilknyttede_brugere.organisation_funktion_registrering_id

    -- Add funktions_type
    LEFT OUTER JOIN organisation_funktion_registrering_funktionstype ON organisation_funktion_registrering.funktionstype_id = organisation_funktion_registrering_funktionstype.id

    -- Add adresse
    LEFT OUTER JOIN organisation_enhed_registrering_adresser ON organisation_enhed_registrering.id = organisation_enhed_registrering_adresser.organisation_enhed_registrering_id
        AND organisation_enhed_registrering_adresser.rolle_label = 'Postadresse'
    LEFT OUTER JOIN adresse_registrering ON organisation_enhed_registrering_adresser.reference_id_uuididentifikator = adresse_registrering.adresse_id
    LEFT OUTER JOIN adresse_registrering_egenskab ON adresse_registrering.id = adresse_registrering_egenskab.adresse_registrering_id
;
SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP VIEW funktions');
    }
}
