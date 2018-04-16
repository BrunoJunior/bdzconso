<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180414215141 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE fuel_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE compatible_fuels (vehicle_id INT NOT NULL, fuel_type_id INT NOT NULL, INDEX IDX_6EE54317545317D1 (vehicle_id), INDEX IDX_6EE543176A70FE35 (fuel_type_id), PRIMARY KEY(vehicle_id, fuel_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fueling (id INT AUTO_INCREMENT NOT NULL, vehicle_id INT DEFAULT NULL, fuel_type_id INT DEFAULT NULL, date DATE NOT NULL, volume DOUBLE PRECISION NOT NULL, volume_price INT NOT NULL, amount INT NOT NULL, traveled_distance INT NOT NULL, INDEX IDX_6169C828545317D1 (vehicle_id), INDEX IDX_6169C8286A70FE35 (fuel_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE compatible_fuels ADD CONSTRAINT FK_6EE54317545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('ALTER TABLE compatible_fuels ADD CONSTRAINT FK_6EE543176A70FE35 FOREIGN KEY (fuel_type_id) REFERENCES fuel_type (id)');
        $this->addSql('ALTER TABLE fueling ADD CONSTRAINT FK_6169C828545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('ALTER TABLE fueling ADD CONSTRAINT FK_6169C8286A70FE35 FOREIGN KEY (fuel_type_id) REFERENCES fuel_type (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE compatible_fuels DROP FOREIGN KEY FK_6EE543176A70FE35');
        $this->addSql('ALTER TABLE fueling DROP FOREIGN KEY FK_6169C8286A70FE35');
        $this->addSql('DROP TABLE fuel_type');
        $this->addSql('DROP TABLE compatible_fuels');
        $this->addSql('DROP TABLE fueling');
    }
}
