<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230818103254 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredient ADD measure_unit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ingredient ADD CONSTRAINT FK_6BAF787063C6A475 FOREIGN KEY (measure_unit_id) REFERENCES measure_unit (id)');
        $this->addSql('CREATE INDEX IDX_6BAF787063C6A475 ON ingredient (measure_unit_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredient DROP FOREIGN KEY FK_6BAF787063C6A475');
        $this->addSql('DROP INDEX IDX_6BAF787063C6A475 ON ingredient');
        $this->addSql('ALTER TABLE ingredient DROP measure_unit_id');
    }
}
