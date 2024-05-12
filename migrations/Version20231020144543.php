<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231020144543 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('INSERT INTO supply_order_state (name, code) VALUES ("Demande en cours", "pending"),("Validée", "validated"),("Refusée", "refused"),("Annulée", "canceled");');
    }

    public function down(Schema $schema): void
    {
        
    }
}