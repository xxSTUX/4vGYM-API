<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240105153502 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity ADD activity_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095AC51EFA73 FOREIGN KEY (activity_type_id) REFERENCES activity_type (id)');
        $this->addSql('CREATE INDEX IDX_AC74095AC51EFA73 ON activity (activity_type_id)');
        $this->addSql('ALTER TABLE activity_monitor ADD activity_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE activity_monitor ADD CONSTRAINT FK_E147EF6581C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('CREATE INDEX IDX_E147EF6581C06096 ON activity_monitor (activity_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095AC51EFA73');
        $this->addSql('DROP INDEX IDX_AC74095AC51EFA73 ON activity');
        $this->addSql('ALTER TABLE activity DROP activity_type_id');
        $this->addSql('ALTER TABLE activity_monitor DROP FOREIGN KEY FK_E147EF6581C06096');
        $this->addSql('DROP INDEX IDX_E147EF6581C06096 ON activity_monitor');
        $this->addSql('ALTER TABLE activity_monitor DROP activity_id');
    }
}
