<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221228114932 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }


    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE posts ADD FOREIGN KEY (author_id) REFERENCES authors(id)');
    }

    public function down(Schema $schema) : void
    {

    }
}
