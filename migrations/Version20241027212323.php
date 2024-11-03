<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241027212323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__meteo_data AS SELECT id, location_id, date, celsius_temperature, cloudiness, icon, flcelsius_temperature, pressure, humidity, wind_speed, wind_direction FROM meteo_data');
        $this->addSql('DROP TABLE meteo_data');
        $this->addSql('CREATE TABLE meteo_data (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, location_id INTEGER NOT NULL, date DATE NOT NULL, celsius_temperature NUMERIC(3, 0) NOT NULL, cloudiness INTEGER DEFAULT NULL, icon VARCHAR(255) DEFAULT NULL, flcelsius_temperature NUMERIC(3, 0) DEFAULT NULL, pressure INTEGER DEFAULT NULL, humidity INTEGER DEFAULT NULL, wind_speed NUMERIC(4, 1) DEFAULT NULL, wind_direction VARCHAR(10) DEFAULT NULL, CONSTRAINT FK_EFA5169F64D218E FOREIGN KEY (location_id) REFERENCES location (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO meteo_data (id, location_id, date, celsius_temperature, cloudiness, icon, flcelsius_temperature, pressure, humidity, wind_speed, wind_direction) SELECT id, location_id, date, celsius_temperature, cloudiness, icon, flcelsius_temperature, pressure, humidity, wind_speed, wind_direction FROM __temp__meteo_data');
        $this->addSql('DROP TABLE __temp__meteo_data');
        $this->addSql('CREATE INDEX IDX_EFA5169F64D218E ON meteo_data (location_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__meteo_data AS SELECT id, location_id, date, celsius_temperature, flcelsius_temperature, pressure, humidity, wind_speed, wind_direction, cloudiness, icon FROM meteo_data');
        $this->addSql('DROP TABLE meteo_data');
        $this->addSql('CREATE TABLE meteo_data (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, location_id INTEGER NOT NULL, date DATE NOT NULL, celsius_temperature NUMERIC(3, 0) NOT NULL, flcelsius_temperature NUMERIC(3, 1) DEFAULT NULL, pressure INTEGER DEFAULT NULL, humidity INTEGER DEFAULT NULL, wind_speed NUMERIC(3, 1) DEFAULT NULL, wind_direction VARCHAR(50) DEFAULT NULL, cloudiness INTEGER DEFAULT NULL, icon VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_EFA5169F64D218E FOREIGN KEY (location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO meteo_data (id, location_id, date, celsius_temperature, flcelsius_temperature, pressure, humidity, wind_speed, wind_direction, cloudiness, icon) SELECT id, location_id, date, celsius_temperature, flcelsius_temperature, pressure, humidity, wind_speed, wind_direction, cloudiness, icon FROM __temp__meteo_data');
        $this->addSql('DROP TABLE __temp__meteo_data');
        $this->addSql('CREATE INDEX IDX_EFA5169F64D218E ON meteo_data (location_id)');
    }
}
