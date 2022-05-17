<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220517182533 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car ADD brand VARCHAR(45) NOT NULL, ADD model VARCHAR(45) NOT NULL, ADD matriculation VARCHAR(10) NOT NULL, ADD matriculation_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD nb_seats INT NOT NULL, ADD nb_doors INT NOT NULL, ADD daily_price NUMERIC(6, 2) NOT NULL, ADD description LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE category ADD name_category VARCHAR(45) NOT NULL');
        $this->addSql('ALTER TABLE energy ADD name_energy VARCHAR(45) NOT NULL');
        $this->addSql('ALTER TABLE equipment ADD name_equipment VARCHAR(45) NOT NULL');
        $this->addSql('ALTER TABLE message ADD content LONGTEXT NOT NULL, ADD subject VARCHAR(45) DEFAULT NULL, ADD seen TINYINT(1) NOT NULL, ADD time TIME DEFAULT NULL COMMENT \'(DC2Type:time_immutable)\', ADD date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE note ADD comment VARCHAR(255) NOT NULL, ADD author VARCHAR(45) NOT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD star NUMERIC(1, 1) DEFAULT NULL');
        $this->addSql('ALTER TABLE renting ADD start DATETIME NOT NULL, ADD end DATETIME NOT NULL, ADD rent_validate TINYINT(1) NOT NULL, ADD payment_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD amout NUMERIC(6, 2) NOT NULL, ADD type_of_payment VARCHAR(45) DEFAULT NULL, ADD planned_duration DATETIME DEFAULT NULL, ADD actual_duration DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD name VARCHAR(45) NOT NULL, ADD firstname VARCHAR(45) NOT NULL, ADD phone_number VARCHAR(10) NOT NULL, ADD country VARCHAR(45) NOT NULL, ADD zip_code VARCHAR(10) NOT NULL, ADD city VARCHAR(45) NOT NULL, ADD address VARCHAR(255) NOT NULL, ADD role VARCHAR(45) DEFAULT NULL, ADD date_of_birth DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD email VARCHAR(255) NOT NULL, ADD pwd VARCHAR(45) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car DROP brand, DROP model, DROP matriculation, DROP matriculation_date, DROP nb_seats, DROP nb_doors, DROP daily_price, DROP description');
        $this->addSql('ALTER TABLE category DROP name_category');
        $this->addSql('ALTER TABLE energy DROP name_energy');
        $this->addSql('ALTER TABLE equipment DROP name_equipment');
        $this->addSql('ALTER TABLE message DROP content, DROP subject, DROP seen, DROP time, DROP date');
        $this->addSql('ALTER TABLE note DROP comment, DROP author, DROP created_at, DROP star');
        $this->addSql('ALTER TABLE renting DROP start, DROP end, DROP rent_validate, DROP payment_date, DROP amout, DROP type_of_payment, DROP planned_duration, DROP actual_duration');
        $this->addSql('ALTER TABLE user DROP name, DROP firstname, DROP phone_number, DROP country, DROP zip_code, DROP city, DROP address, DROP role, DROP date_of_birth, DROP email, DROP pwd');
    }
}
