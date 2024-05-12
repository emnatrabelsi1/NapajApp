<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231003162331 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398B171EB6C');
        $this->addSql('DROP INDEX IDX_F5299398B171EB6C ON `order`');
        $this->addSql('ALTER TABLE `order` CHANGE customer_id_id customer_id INT NOT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993989395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('CREATE INDEX IDX_F52993989395C3F3 ON `order` (customer_id)');
        $this->addSql('ALTER TABLE order_line DROP FOREIGN KEY FK_9CE58EE1DE18E50B');
        $this->addSql('ALTER TABLE order_line DROP FOREIGN KEY FK_9CE58EE1FCDAEAAA');
        $this->addSql('DROP INDEX IDX_9CE58EE1FCDAEAAA ON order_line');
        $this->addSql('DROP INDEX IDX_9CE58EE1DE18E50B ON order_line');
        $this->addSql('ALTER TABLE order_line ADD order_id INT NOT NULL, ADD product_id INT NOT NULL, DROP order_id_id, DROP product_id_id');
        $this->addSql('ALTER TABLE order_line ADD CONSTRAINT FK_9CE58EE18D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE order_line ADD CONSTRAINT FK_9CE58EE14584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_9CE58EE18D9F6D38 ON order_line (order_id)');
        $this->addSql('CREATE INDEX IDX_9CE58EE14584665A ON order_line (product_id)');
        $this->addSql('ALTER TABLE product CHANGE piece_quantity piece_quantity NUMERIC(8, 2) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_line DROP FOREIGN KEY FK_9CE58EE18D9F6D38');
        $this->addSql('ALTER TABLE order_line DROP FOREIGN KEY FK_9CE58EE14584665A');
        $this->addSql('DROP INDEX IDX_9CE58EE18D9F6D38 ON order_line');
        $this->addSql('DROP INDEX IDX_9CE58EE14584665A ON order_line');
        $this->addSql('ALTER TABLE order_line ADD order_id_id INT NOT NULL, ADD product_id_id INT NOT NULL, DROP order_id, DROP product_id');
        $this->addSql('ALTER TABLE order_line ADD CONSTRAINT FK_9CE58EE1DE18E50B FOREIGN KEY (product_id_id) REFERENCES product (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE order_line ADD CONSTRAINT FK_9CE58EE1FCDAEAAA FOREIGN KEY (order_id_id) REFERENCES `order` (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_9CE58EE1FCDAEAAA ON order_line (order_id_id)');
        $this->addSql('CREATE INDEX IDX_9CE58EE1DE18E50B ON order_line (product_id_id)');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993989395C3F3');
        $this->addSql('DROP INDEX IDX_F52993989395C3F3 ON `order`');
        $this->addSql('ALTER TABLE `order` CHANGE customer_id customer_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398B171EB6C FOREIGN KEY (customer_id_id) REFERENCES customer (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_F5299398B171EB6C ON `order` (customer_id_id)');
        $this->addSql('ALTER TABLE product CHANGE piece_quantity piece_quantity NUMERIC(8, 2) NOT NULL');
    }
}
