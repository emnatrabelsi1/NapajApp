<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231020144542 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE supply_order (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, state_id INT NOT NULL, created_at DATETIME NOT NULL, amount NUMERIC(8, 2) DEFAULT NULL, INDEX IDX_91F9D33CA76ED395 (user_id), INDEX IDX_91F9D33C5D83CC1 (state_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE supply_order_state (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE supply_order ADD CONSTRAINT FK_91F9D33CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE supply_order ADD CONSTRAINT FK_91F9D33C5D83CC1 FOREIGN KEY (state_id) REFERENCES supply_order_state (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE supply_order DROP FOREIGN KEY FK_91F9D33CA76ED395');
        $this->addSql('ALTER TABLE supply_order DROP FOREIGN KEY FK_91F9D33C5D83CC1');
        $this->addSql('DROP TABLE supply_order');
        $this->addSql('DROP TABLE supply_order_state');
    }
}
