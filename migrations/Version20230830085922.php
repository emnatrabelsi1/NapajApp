<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230830085922 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipe_cutting ADD recipe_id INT NOT NULL');
        $this->addSql('ALTER TABLE recipe_cutting ADD CONSTRAINT FK_B798288D59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('CREATE INDEX IDX_B798288D59D8A214 ON recipe_cutting (recipe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipe_cutting DROP FOREIGN KEY FK_B798288D59D8A214');
        $this->addSql('DROP INDEX IDX_B798288D59D8A214 ON recipe_cutting');
        $this->addSql('ALTER TABLE recipe_cutting DROP recipe_id');
    }
}
