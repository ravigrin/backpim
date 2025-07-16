<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231211061205 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE influence_field (field_id INT IDENTITY NOT NULL, table_id INT NOT NULL, title NVARCHAR(1000) NOT NULL, alias NVARCHAR(1000) NOT NULL, formula NVARCHAR(1000) NOT NULL, type NVARCHAR(255) NOT NULL, is_deleted BIT NOT NULL, PRIMARY KEY (field_id))');
        $this->addSql('CREATE TABLE influence_table (table_id INT IDENTITY NOT NULL, title NVARCHAR(1000) NOT NULL, alias NVARCHAR(1000) NOT NULL, row_count INT NOT NULL, custom_order INT NOT NULL, is_deleted BIT NOT NULL, PRIMARY KEY (table_id))');
        $this->addSql('CREATE TABLE influence_value (value_id INT IDENTITY NOT NULL, table_id INT NOT NULL, field_id INT NOT NULL, row INT NOT NULL, value NVARCHAR(2000) NOT NULL, PRIMARY KEY (value_id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
