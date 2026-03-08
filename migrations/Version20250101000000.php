<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250101000000 extends AbstractMigration
{
  public function getDescription(): string
  {
    return 'Create initial tables for The Klassiker application';
  }

  public function up(Schema $schema): void
  {
    // Table user
    $this->addSql('CREATE TABLE `user` (
            id INT AUTO_INCREMENT NOT NULL,
            email VARCHAR(180) NOT NULL,
            roles JSON NOT NULL,
            password VARCHAR(255) NOT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME DEFAULT NULL,
            UNIQUE INDEX UNIQ_8D93D649E7927C74 (email),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

    // Table category
    $this->addSql('CREATE TABLE category (
            id INT AUTO_INCREMENT NOT NULL,
            name VARCHAR(100) NOT NULL,
            slug VARCHAR(110) NOT NULL,
            description TEXT DEFAULT NULL,
            display_order INT NOT NULL DEFAULT 0,
            is_active TINYINT(1) NOT NULL DEFAULT 1,
            created_at DATETIME NOT NULL,
            updated_at DATETIME DEFAULT NULL,
            UNIQUE INDEX UNIQ_64C19C72989D9B62 (slug),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

    // Table dish
    $this->addSql('CREATE TABLE dish (
            id INT AUTO_INCREMENT NOT NULL,
            category_id INT NOT NULL,
            name VARCHAR(150) NOT NULL,
            description TEXT NOT NULL,
            price DECIMAL(6,2) NOT NULL,
            image VARCHAR(255) DEFAULT NULL,
            display_order INT NOT NULL DEFAULT 0,
            is_active TINYINT(1) NOT NULL DEFAULT 1,
            is_featured TINYINT(1) NOT NULL DEFAULT 0,
            created_at DATETIME NOT NULL,
            updated_at DATETIME DEFAULT NULL,
            INDEX IDX_4D37422F12469DE2 (category_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

    // Table gallery_photo
    $this->addSql('CREATE TABLE gallery_photo (
            id INT AUTO_INCREMENT NOT NULL,
            filename VARCHAR(255) NOT NULL,
            alt_text VARCHAR(255) DEFAULT NULL,
            display_order INT NOT NULL DEFAULT 0,
            is_active TINYINT(1) NOT NULL DEFAULT 1,
            created_at DATETIME NOT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

    // Table contact_message
    $this->addSql('CREATE TABLE contact_message (
            id INT AUTO_INCREMENT NOT NULL,
            first_name VARCHAR(100) NOT NULL,
            last_name VARCHAR(100) NOT NULL,
            email VARCHAR(180) NOT NULL,
            phone VARCHAR(20) DEFAULT NULL,
            subject VARCHAR(200) NOT NULL,
            message TEXT NOT NULL,
            is_read TINYINT(1) NOT NULL DEFAULT 0,
            created_at DATETIME NOT NULL,
            INDEX IDX_6C3BF17F9B6B5F4 (email),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

    // Table opening_hours
    $this->addSql('CREATE TABLE opening_hours (
            id INT AUTO_INCREMENT NOT NULL,
            day_name VARCHAR(20) NOT NULL,
            day_of_week INT NOT NULL,
            morning_open TIME DEFAULT NULL,
            morning_close TIME DEFAULT NULL,
            afternoon_open TIME DEFAULT NULL,
            afternoon_close TIME DEFAULT NULL,
            is_closed TINYINT(1) NOT NULL DEFAULT 0,
            display_order INT NOT NULL DEFAULT 0,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

    // Foreign keys
    $this->addSql('ALTER TABLE dish ADD CONSTRAINT FK_4D37422F12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
  }

  public function down(Schema $schema): void
  {
    $this->addSql('ALTER TABLE dish DROP FOREIGN KEY FK_4D37422F12469DE2');
    $this->addSql('DROP TABLE `user`');
    $this->addSql('DROP TABLE category');
    $this->addSql('DROP TABLE dish');
    $this->addSql('DROP TABLE gallery_photo');
    $this->addSql('DROP TABLE contact_message');
    $this->addSql('DROP TABLE opening_hours');
  }
}

