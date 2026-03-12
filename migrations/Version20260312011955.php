<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260312011955 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE marquee_item (id INT AUTO_INCREMENT NOT NULL, text VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, display_order INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP INDEX IDX_8EC8A3D7764045 ON `order`');
        $this->addSql('DROP INDEX IDX_8EC8A3D7A72D3B5A ON `order`');
        $this->addSql('ALTER TABLE `order` CHANGE delivery_address delivery_address LONGTEXT DEFAULT NULL, CHANGE comments comments LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52F1C8A7A72D3B5A');
        $this->addSql('ALTER TABLE order_item CHANGE special_instructions special_instructions LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F09E415FB15 FOREIGN KEY (order_item_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE order_item RENAME INDEX idx_52f1c8a7a72d3b5a TO IDX_52EA1F09E415FB15');
        $this->addSql('ALTER TABLE order_item RENAME INDEX idx_52f1c8a7e80b1ac TO IDX_52EA1F09148EB0CB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE marquee_item');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F09E415FB15');
        $this->addSql('ALTER TABLE order_item CHANGE special_instructions special_instructions TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52F1C8A7A72D3B5A FOREIGN KEY (order_item_id) REFERENCES `order` (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_item RENAME INDEX idx_52ea1f09e415fb15 TO IDX_52F1C8A7A72D3B5A');
        $this->addSql('ALTER TABLE order_item RENAME INDEX idx_52ea1f09148eb0cb TO IDX_52F1C8A7E80B1AC');
        $this->addSql('ALTER TABLE `order` CHANGE delivery_address delivery_address TEXT DEFAULT NULL, CHANGE comments comments TEXT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_8EC8A3D7764045 ON `order` (status)');
        $this->addSql('CREATE INDEX IDX_8EC8A3D7A72D3B5A ON `order` (type)');
    }
}
