<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231013133320 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company CHANGE is_napaj is_napaj TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE product ADD recipe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD59D8A214 ON product (recipe_id)');
        $this->addSql('ALTER TABLE recipe ADD description LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company CHANGE is_napaj is_napaj TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE recipe DROP description');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD59D8A214');
        $this->addSql('DROP INDEX IDX_D34A04AD59D8A214 ON product');
        $this->addSql('ALTER TABLE product DROP recipe_id');
    }
}
