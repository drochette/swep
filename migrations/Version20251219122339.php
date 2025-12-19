<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251219122339 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add campus_id to vehicle table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE vehicle ADD campus_id INT NOT NULL');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E486AF5D55E1 FOREIGN KEY (campus_id) REFERENCES campus (id)');
        $this->addSql('CREATE INDEX IDX_1B80E486AF5D55E1 ON vehicle (campus_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E486AF5D55E1');
        $this->addSql('DROP INDEX IDX_1B80E486AF5D55E1 ON vehicle');
        $this->addSql('ALTER TABLE vehicle DROP campus_id');
    }
}
