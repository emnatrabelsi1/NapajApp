<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230915092130 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE supplier_price DROP FOREIGN KEY FK_13D320F3979B1AD6');
        $this->addSql('DROP INDEX IDX_13D320F3979B1AD6 ON supplier_price');
        $this->addSql('ALTER TABLE supplier_price DROP company_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE supplier_price ADD company_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE supplier_price ADD CONSTRAINT FK_13D320F3979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_13D320F3979B1AD6 ON supplier_price (company_id)');
    }
}
