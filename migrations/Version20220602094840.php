<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220602094840 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE renting ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE renting ADD CONSTRAINT FK_13533C0FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_13533C0FA76ED395 ON renting (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE renting DROP FOREIGN KEY FK_13533C0FA76ED395');
        $this->addSql('DROP INDEX IDX_13533C0FA76ED395 ON renting');
        $this->addSql('ALTER TABLE renting DROP user_id');
    }
}
