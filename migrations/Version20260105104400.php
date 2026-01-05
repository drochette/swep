<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260105104400 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create vehicle booking table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<SQL
            CREATE TABLE vehicle_booking (
                id INT AUTO_INCREMENT NOT NULL,
                start_at DATETIME NOT NULL,
                end_at DATETIME NOT NULL,
                booked_by_id INT NOT NULL,
                vehicle_id INT NOT NULL,
                INDEX IDX_FF7FB0B1F4A5BD90 (booked_by_id),
                INDEX IDX_FF7FB0B1545317D1 (vehicle_id),
                PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4
SQL);

        $this->addSql('ALTER TABLE vehicle_booking ADD CONSTRAINT FK_FF7FB0B1F4A5BD90 FOREIGN KEY (booked_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE vehicle_booking ADD CONSTRAINT FK_FF7FB0B1545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('CREATE INDEX vehicle_label_idx ON vehicle (label)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vehicle_booking DROP FOREIGN KEY FK_FF7FB0B1F4A5BD90');
        $this->addSql('ALTER TABLE vehicle_booking DROP FOREIGN KEY FK_FF7FB0B1545317D1');
        $this->addSql('DROP TABLE vehicle_booking');
        $this->addSql('DROP INDEX vehicle_label_idx ON vehicle');
    }
}
