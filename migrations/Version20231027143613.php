<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231027143613 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE noncompliance (id INT AUTO_INCREMENT NOT NULL, noncompliance_type_id INT NOT NULL, relative_order_id INT DEFAULT NULL, noncompliance_state_id INT NOT NULL, declarant VARCHAR(255) NOT NULL, comment LONGTEXT DEFAULT NULL, declaration_date DATETIME NOT NULL, processing_date DATETIME DEFAULT NULL, processing_comment LONGTEXT DEFAULT NULL, assigned VARCHAR(255) DEFAULT NULL, INDEX IDX_E4464B8EF6549C15 (noncompliance_type_id), INDEX IDX_E4464B8E87DF5639 (relative_order_id), INDEX IDX_E4464B8E5B766510 (noncompliance_state_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE noncompliance_state (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE noncompliance_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE noncompliance ADD CONSTRAINT FK_E4464B8EF6549C15 FOREIGN KEY (noncompliance_type_id) REFERENCES noncompliance_type (id)');
        $this->addSql('ALTER TABLE noncompliance ADD CONSTRAINT FK_E4464B8E87DF5639 FOREIGN KEY (relative_order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE noncompliance ADD CONSTRAINT FK_E4464B8E5B766510 FOREIGN KEY (noncompliance_state_id) REFERENCES noncompliance_state (id)');
        $this->addSql("INSERT INTO noncompliance_type (name) VALUES ('Respect recette'), ('Finition'),( 'Autre')");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE noncompliance DROP FOREIGN KEY FK_E4464B8EF6549C15');
        $this->addSql('ALTER TABLE noncompliance DROP FOREIGN KEY FK_E4464B8E87DF5639');
        $this->addSql('ALTER TABLE noncompliance DROP FOREIGN KEY FK_E4464B8E5B766510');
        $this->addSql('DROP TABLE noncompliance');
        $this->addSql('DROP TABLE noncompliance_state');
        $this->addSql('DROP TABLE noncompliance_type');
    }
}
