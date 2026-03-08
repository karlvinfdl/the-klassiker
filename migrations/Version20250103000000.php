<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250103000000 extends AbstractMigration
{
  public function getDescription(): string
  {
    return 'Add firstName to user table';
  }

  public function up(Schema $schema): void
  {
    $this->addSql('ALTER TABLE `user` ADD first_name VARCHAR(100) NOT NULL');
  }

  public function down(Schema $schema): void
  {
    $this->addSql('ALTER TABLE `user` DROP first_name');
  }
}

