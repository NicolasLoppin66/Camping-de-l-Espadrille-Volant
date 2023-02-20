<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230220134727 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bookings ADD client_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bookings ADD CONSTRAINT FK_7A853C35DC2902E0 FOREIGN KEY (client_id_id) REFERENCES clients (id)');
        $this->addSql('CREATE INDEX IDX_7A853C35DC2902E0 ON bookings (client_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bookings DROP FOREIGN KEY FK_7A853C35DC2902E0');
        $this->addSql('DROP INDEX IDX_7A853C35DC2902E0 ON bookings');
        $this->addSql('ALTER TABLE bookings DROP client_id_id');
    }
}
