<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230818095707 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE preparation_ingredient (id INT AUTO_INCREMENT NOT NULL, preparation_id INT NOT NULL, ingredient_id INT NOT NULL, weight NUMERIC(8, 3) DEFAULT NULL, quantity NUMERIC(10, 0) DEFAULT NULL, INDEX IDX_F956CA053DD9B8BA (preparation_id), INDEX IDX_F956CA05933FE08C (ingredient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE preparation_ingredient ADD CONSTRAINT FK_F956CA053DD9B8BA FOREIGN KEY (preparation_id) REFERENCES preparation (id)');
        $this->addSql('ALTER TABLE preparation_ingredient ADD CONSTRAINT FK_F956CA05933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE preparation_ingredient DROP FOREIGN KEY FK_F956CA053DD9B8BA');
        $this->addSql('ALTER TABLE preparation_ingredient DROP FOREIGN KEY FK_F956CA05933FE08C');
        $this->addSql('DROP TABLE preparation_ingredient');
    }
}
