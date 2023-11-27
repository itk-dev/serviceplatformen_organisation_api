<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231124091925 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE OR REPLACE VIEW organisation AS
    SELECT
        organisation_enhed_registrering.organisation_enhed_id AS id,
        organisation_enhed_registrering_egenskab.enhed_navn AS enhedsnavn,
        organisation_enhed_registrering_overordnet.reference_id_uuididentifikator AS overordnet_id
    FROM organisation_enhed_registrering

    -- Add enhedsnavn
    LEFT OUTER JOIN organisation_enhed_registrering_egenskab ON organisation_enhed_registrering.id = organisation_enhed_registrering_egenskab.organisation_enhed_registrering_id

    -- Add overordnet_id
    LEFT OUTER JOIN organisation_enhed_registrering_overordnet ON organisation_enhed_registrering.overordnet_id = organisation_enhed_registrering_overordnet.id
;
SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP VIEW organisation');
    }
}
