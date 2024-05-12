<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231027152854 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO noncompliance_state (name, code) VALUES ('Non traité', 'OPEN'),('Traité', 'DONE'),('Départ client', 'CUSTOMER_DELIVERY'),('Perte', 'LOSS')");
    }

    public function down(Schema $schema): void
    {
    }
}
