<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231230223252 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredient CHANGE is_demo is_demo TINYINT(1) NOT NULL, CHANGE is_disabled is_disabled TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE preparation_ingredient ADD volume NUMERIC(8, 3) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredient CHANGE is_demo is_demo TINYINT(1) DEFAULT 0 NOT NULL, CHANGE is_disabled is_disabled TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE preparation_ingredient DROP volume');
    }
}
