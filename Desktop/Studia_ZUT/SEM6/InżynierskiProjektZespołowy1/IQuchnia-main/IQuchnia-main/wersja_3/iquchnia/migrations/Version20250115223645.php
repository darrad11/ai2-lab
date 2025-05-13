<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250115223645 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__recipe AS SELECT id, user_id, name, instructions, cuisine, difficulty, description, image, isFavorite, views FROM recipe');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('CREATE TABLE recipe (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, instructions CLOB NOT NULL, cuisine VARCHAR(255) NOT NULL, difficulty VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, image CLOB NOT NULL, is_vegetarian BOOLEAN NOT NULL, views INTEGER NOT NULL, ingredients CLOB DEFAULT NULL, CONSTRAINT FK_DA88B137A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO recipe (id, user_id, name, instructions, cuisine, difficulty, description, image, is_vegetarian, views) SELECT id, user_id, name, instructions, cuisine, difficulty, description, image, isFavorite, views FROM __temp__recipe');
        $this->addSql('DROP TABLE __temp__recipe');
        $this->addSql('CREATE INDEX IDX_DA88B137A76ED395 ON recipe (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__recipe AS SELECT id, name, instructions, cuisine, difficulty, is_vegetarian, image, description, views, user_id FROM recipe');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('CREATE TABLE recipe (id INTEGER PRIMARY KEY AUTOINCREMENT DEFAULT NULL, name CLOB NOT NULL, instructions CLOB NOT NULL, cuisine CLOB NOT NULL, difficulty CLOB NOT NULL, isFavorite BOOLEAN NOT NULL, image CLOB NOT NULL, description CLOB NOT NULL, views INTEGER NOT NULL, user_id INTEGER NOT NULL, diet CLOB NOT NULL, people INTEGER NOT NULL)');
        $this->addSql('INSERT INTO recipe (id, name, instructions, cuisine, difficulty, isFavorite, image, description, views, user_id) SELECT id, name, instructions, cuisine, difficulty, is_vegetarian, image, description, views, user_id FROM __temp__recipe');
        $this->addSql('DROP TABLE __temp__recipe');
    }
}
