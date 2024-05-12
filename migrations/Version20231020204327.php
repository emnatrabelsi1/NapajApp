<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231020204327 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE supply_order ADD company_id INT NOT NULL');
        $this->addSql('ALTER TABLE supply_order ADD CONSTRAINT FK_91F9D33C979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('CREATE INDEX IDX_91F9D33C979B1AD6 ON supply_order (company_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE supply_order DROP FOREIGN KEY FK_91F9D33C979B1AD6');
        $this->addSql('DROP INDEX IDX_91F9D33C979B1AD6 ON supply_order');
        $this->addSql('ALTER TABLE supply_order DROP company_id');
    }
}
