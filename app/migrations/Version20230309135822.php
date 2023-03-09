<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230309135822 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE addresses (id INT AUTO_INCREMENT NOT NULL, num INT NOT NULL, road_type VARCHAR(50) NOT NULL, road_name VARCHAR(255) NOT NULL, zip INT NOT NULL, city VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bill_lines (id INT AUTO_INCREMENT NOT NULL, booking_id INT NOT NULL, label VARCHAR(255) NOT NULL, quantity INT NOT NULL, pu NUMERIC(5, 2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bills (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, address_id INT NOT NULL, rental_name VARCHAR(255) NOT NULL, pool_adult_pu NUMERIC(10, 0) NOT NULL, pool_kid_pu NUMERIC(10, 0) NOT NULL, ts_adult_pu NUMERIC(10, 0) NOT NULL, ts_kid_pu NUMERIC(10, 0) NOT NULL, nb_adult INT NOT NULL, nb_kid INT NOT NULL, check_in DATE NOT NULL, check_out DATE NOT NULL, pool_adult_nb INT NOT NULL, pool_kid_nb INT NOT NULL, product_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bookings (id INT AUTO_INCREMENT NOT NULL, product_id_id INT DEFAULT NULL, client_id_id INT DEFAULT NULL, check_in DATE NOT NULL, check_out DATE NOT NULL, nb_adults INT NOT NULL, nb_kids INT NOT NULL, pool_access_adults INT NOT NULL, pool_access_kids INT NOT NULL, discount NUMERIC(6, 2) NOT NULL, INDEX IDX_7A853C35DE18E50B (product_id_id), INDEX IDX_7A853C35DC2902E0 (client_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clients (id INT AUTO_INCREMENT NOT NULL, address_id_id INT NOT NULL, first_name VARCHAR(150) NOT NULL, last_name VARCHAR(150) NOT NULL, email VARCHAR(200) NOT NULL, telephone VARCHAR(50) NOT NULL, erase_data_day DATE NOT NULL, data_retention_consent TINYINT(1) NOT NULL, INDEX IDX_C82E7448E1E977 (address_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE disponibilites (id INT AUTO_INCREMENT NOT NULL, product_id_id INT DEFAULT NULL, day VARCHAR(255) NOT NULL, is_booked TINYINT(1) NOT NULL, INDEX IDX_B0F3489CDE18E50B (product_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE extra_charges (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(150) NOT NULL, amount_adults NUMERIC(4, 2) NOT NULL, amount_kids NUMERIC(4, 2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE owners (id INT AUTO_INCREMENT NOT NULL, address_id_id INT DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, telephone VARCHAR(50) NOT NULL, password VARCHAR(255) NOT NULL, date_retention_consent TINYINT(1) NOT NULL, role VARCHAR(255) NOT NULL, INDEX IDX_427292FA48E1E977 (address_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE owners_contracts (id INT AUTO_INCREMENT NOT NULL, product_id_id INT NOT NULL, owner_id_id INT NOT NULL, contract_date DATE NOT NULL, INDEX IDX_9A208C3BDE18E50B (product_id_id), INDEX IDX_9A208C3B8FDDAB70 (owner_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products (id INT AUTO_INCREMENT NOT NULL, owner_id_id INT NOT NULL, rental_type_id INT NOT NULL, label VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_B3BA5A5A8FDDAB70 (owner_id_id), INDEX IDX_B3BA5A5A16AA567C (rental_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rentals_photos (id INT AUTO_INCREMENT NOT NULL, product_id_id INT DEFAULT NULL, img VARCHAR(255) NOT NULL, INDEX IDX_8C7AAAACDE18E50B (product_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rentals_types (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, price NUMERIC(5, 2) NOT NULL, capacity VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE session_periods (id INT AUTO_INCREMENT NOT NULL, begin DATE NOT NULL, end DATE NOT NULL, increase INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bookings ADD CONSTRAINT FK_7A853C35DE18E50B FOREIGN KEY (product_id_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE bookings ADD CONSTRAINT FK_7A853C35DC2902E0 FOREIGN KEY (client_id_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE clients ADD CONSTRAINT FK_C82E7448E1E977 FOREIGN KEY (address_id_id) REFERENCES addresses (id)');
        $this->addSql('ALTER TABLE disponibilites ADD CONSTRAINT FK_B0F3489CDE18E50B FOREIGN KEY (product_id_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE owners ADD CONSTRAINT FK_427292FA48E1E977 FOREIGN KEY (address_id_id) REFERENCES addresses (id)');
        $this->addSql('ALTER TABLE owners_contracts ADD CONSTRAINT FK_9A208C3BDE18E50B FOREIGN KEY (product_id_id) REFERENCES owners (id)');
        $this->addSql('ALTER TABLE owners_contracts ADD CONSTRAINT FK_9A208C3B8FDDAB70 FOREIGN KEY (owner_id_id) REFERENCES owners (id)');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A8FDDAB70 FOREIGN KEY (owner_id_id) REFERENCES owners (id)');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A16AA567C FOREIGN KEY (rental_type_id) REFERENCES rentals_types (id)');
        $this->addSql('ALTER TABLE rentals_photos ADD CONSTRAINT FK_8C7AAAACDE18E50B FOREIGN KEY (product_id_id) REFERENCES products (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bookings DROP FOREIGN KEY FK_7A853C35DE18E50B');
        $this->addSql('ALTER TABLE bookings DROP FOREIGN KEY FK_7A853C35DC2902E0');
        $this->addSql('ALTER TABLE clients DROP FOREIGN KEY FK_C82E7448E1E977');
        $this->addSql('ALTER TABLE disponibilites DROP FOREIGN KEY FK_B0F3489CDE18E50B');
        $this->addSql('ALTER TABLE owners DROP FOREIGN KEY FK_427292FA48E1E977');
        $this->addSql('ALTER TABLE owners_contracts DROP FOREIGN KEY FK_9A208C3BDE18E50B');
        $this->addSql('ALTER TABLE owners_contracts DROP FOREIGN KEY FK_9A208C3B8FDDAB70');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A8FDDAB70');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A16AA567C');
        $this->addSql('ALTER TABLE rentals_photos DROP FOREIGN KEY FK_8C7AAAACDE18E50B');
        $this->addSql('DROP TABLE addresses');
        $this->addSql('DROP TABLE bill_lines');
        $this->addSql('DROP TABLE bills');
        $this->addSql('DROP TABLE bookings');
        $this->addSql('DROP TABLE clients');
        $this->addSql('DROP TABLE disponibilites');
        $this->addSql('DROP TABLE extra_charges');
        $this->addSql('DROP TABLE owners');
        $this->addSql('DROP TABLE owners_contracts');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE rentals_photos');
        $this->addSql('DROP TABLE rentals_types');
        $this->addSql('DROP TABLE session_periods');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
