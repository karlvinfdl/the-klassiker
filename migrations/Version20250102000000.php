<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250102000000 extends AbstractMigration
{
  public function getDescription(): string
  {
    return 'Create order and order_item tables';
  }

  public function up(Schema $schema): void
  {
    // Create order table
    $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, status VARCHAR(20) NOT NULL, type VARCHAR(20) NOT NULL, customer_name VARCHAR(150) NOT NULL, customer_phone VARCHAR(20) NOT NULL, customer_email VARCHAR(255) DEFAULT NULL, delivery_address TEXT DEFAULT NULL, comments TEXT DEFAULT NULL, total_amount DECIMAL(10,2) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_8EC8A3D7764045 (status), INDEX IDX_8EC8A3D7A72D3B5A (type), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

    // Create order_item table
    $this->addSql('CREATE TABLE order_item (id INT AUTO_INCREMENT NOT NULL, order_item_id INT NOT NULL, dish_id INT NOT NULL, dish_name VARCHAR(150) NOT NULL, unit_price DECIMAL(6,2) NOT NULL, quantity INT NOT NULL, special_instructions TEXT DEFAULT NULL, INDEX IDX_52F1C8A7A72D3B5A (order_item_id), INDEX IDX_52F1C8A7E80B1AC (dish_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

    // Add foreign keys
    $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52F1C8A7A72D3B5A FOREIGN KEY (order_item_id) REFERENCES `order` (id) ON DELETE CASCADE');
    $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52F1C8A7E80B1AC FOREIGN KEY (dish_id) REFERENCES dish (id)');
  }

  public function down(Schema $schema): void
  {
    $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52F1C8A7A72D3B5A');
    $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52F1C8A7E80B1AC');
    $this->addSql('DROP TABLE order_item');
    $this->addSql('DROP TABLE `order`');
  }
}

