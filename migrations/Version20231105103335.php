<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231105103335 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE customer_tag (customer_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_4C7AE9759395C3F3 (customer_id), INDEX IDX_4C7AE975BAD26311 (tag_id), PRIMARY KEY(customer_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE customer_tag ADD CONSTRAINT FK_4C7AE9759395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE customer_tag ADD CONSTRAINT FK_4C7AE975BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag ADD public TINYINT(1) NOT NULL');
        $this->addSql("UPDATE tag set public = 1 WHERE name = 'Catalogue'");        
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer_tag DROP FOREIGN KEY FK_4C7AE9759395C3F3');
        $this->addSql('ALTER TABLE customer_tag DROP FOREIGN KEY FK_4C7AE975BAD26311');
        $this->addSql('DROP TABLE customer_tag');
        $this->addSql('ALTER TABLE tag DROP public');
    }
}
