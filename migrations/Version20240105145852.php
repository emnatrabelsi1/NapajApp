<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240105145852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipe_composition ADD ingredient_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE recipe_composition ADD CONSTRAINT FK_D88D0D23933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id)');
        $this->addSql('CREATE INDEX IDX_D88D0D23933FE08C ON recipe_composition (ingredient_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipe_composition DROP FOREIGN KEY FK_D88D0D23933FE08C');
        $this->addSql('DROP INDEX IDX_D88D0D23933FE08C ON recipe_composition');
        $this->addSql('ALTER TABLE recipe_composition DROP ingredient_id');
    }
}
