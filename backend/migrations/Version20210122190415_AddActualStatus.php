<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210122190415_AddActualStatus extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE videos ADD status boolean DEFAULT true;');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE videos DROP status;');
    }
}
