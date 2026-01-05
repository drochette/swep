<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260105095158 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add index on campus label';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE INDEX campus_label_idx ON campus (label)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX campus_label_idx ON campus');
    }
}
