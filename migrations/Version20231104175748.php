<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231104175748 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company ADD relative_customer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FD8390766 FOREIGN KEY (relative_customer_id) REFERENCES customer (id)');
        $this->addSql('CREATE INDEX IDX_4FBF094FD8390766 ON company (relative_customer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094FD8390766');
        $this->addSql('DROP INDEX IDX_4FBF094FD8390766 ON company');
        $this->addSql('ALTER TABLE company DROP relative_customer_id');
    }
}
