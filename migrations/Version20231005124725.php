<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231005124725 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE allergen (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ingredient ADD allergen_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ingredient ADD CONSTRAINT FK_6BAF78706E775A4A FOREIGN KEY (allergen_id) REFERENCES allergen (id)');
        $this->addSql('CREATE INDEX IDX_6BAF78706E775A4A ON ingredient (allergen_id)');
        $this->addSql('INSERT INTO allergen (name) VALUES ("Gluten"),("Oeufs"),("Arachides"),("Soja"),("Lait"),("Fruits à coques"),("Sésame"),("Lupin");');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredient DROP FOREIGN KEY FK_6BAF78706E775A4A');
        $this->addSql('DROP TABLE allergen');
        $this->addSql('DROP INDEX IDX_6BAF78706E775A4A ON ingredient');
        $this->addSql('ALTER TABLE ingredient DROP allergen_id');
    }
}
