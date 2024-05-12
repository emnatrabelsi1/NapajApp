<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231027152853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE noncompliance RENAME INDEX idx_e4464b8ef6549c15 TO IDX_E4464B8EB5EC53EB');
        $this->addSql('ALTER TABLE noncompliance_state ADD code VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE noncompliance RENAME INDEX idx_e4464b8eb5ec53eb TO IDX_E4464B8EF6549C15');
        $this->addSql('ALTER TABLE noncompliance_state DROP code');
    }
}
