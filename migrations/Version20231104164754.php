<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231104164754 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_tag (product_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_E3A6E39C4584665A (product_id), INDEX IDX_E3A6E39CBAD26311 (tag_id), PRIMARY KEY(product_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_tag ADD CONSTRAINT FK_E3A6E39C4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_tag ADD CONSTRAINT FK_E3A6E39CBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE company ADD feature_product TINYINT(1) NOT NULL, ADD feature_order TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE `order` ADD company_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('CREATE INDEX IDX_F5299398979B1AD6 ON `order` (company_id)');
        $this->addSql('ALTER TABLE product DROP is_for_restaurant');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');

        $this->addSql('UPDATE company SET is_napaj = true WHERE id = 3');
        $this->addSql('UPDATE company SET feature_order = true WHERE id = 1');
        $this->addSql('UPDATE company SET feature_product = true WHERE id = 1');
        $this->addSql('UPDATE `order` SET company_id = 1 where id > 0');
        $this->addSql("INSERT INTO tag (name) VALUES ('Bordeaux'),('Catalogue'),('Stock Labo'),('Pirate'),('Pomme Cannelle')");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_tag DROP FOREIGN KEY FK_E3A6E39C4584665A');
        $this->addSql('ALTER TABLE product_tag DROP FOREIGN KEY FK_E3A6E39CBAD26311');
        $this->addSql('DROP TABLE product_tag');
        $this->addSql('DROP TABLE tag');
        $this->addSql('ALTER TABLE company DROP feature_product, DROP feature_order');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398979B1AD6');
        $this->addSql('DROP INDEX IDX_F5299398979B1AD6 ON `order`');
        $this->addSql('ALTER TABLE `order` DROP company_id');
        $this->addSql('ALTER TABLE product ADD is_for_restaurant TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }
}
