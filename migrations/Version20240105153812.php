<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240105153812 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity_monitor ADD monitor_id INT NOT NULL');
        $this->addSql('ALTER TABLE activity_monitor ADD CONSTRAINT FK_E147EF654CE1C902 FOREIGN KEY (monitor_id) REFERENCES monitor (id)');
        $this->addSql('CREATE INDEX IDX_E147EF654CE1C902 ON activity_monitor (monitor_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity_monitor DROP FOREIGN KEY FK_E147EF654CE1C902');
        $this->addSql('DROP INDEX IDX_E147EF654CE1C902 ON activity_monitor');
        $this->addSql('ALTER TABLE activity_monitor DROP monitor_id');
    }
}
