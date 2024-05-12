<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231020200955 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE supply_order ADD supplier_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE supply_order ADD CONSTRAINT FK_91F9D33C2ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('CREATE INDEX IDX_91F9D33C2ADD6D8C ON supply_order (supplier_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE supply_order DROP FOREIGN KEY FK_91F9D33C2ADD6D8C');
        $this->addSql('DROP INDEX IDX_91F9D33C2ADD6D8C ON supply_order');
        $this->addSql('ALTER TABLE supply_order DROP supplier_id');
    }
}
