<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180511213816 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE partial_fueling (id INT AUTO_INCREMENT NOT NULL, vehicle_id INT DEFAULT NULL, fuel_type_id INT DEFAULT NULL, date DATE NOT NULL, volume INT NOT NULL, volume_price INT NOT NULL, amount INT NOT NULL, additived_fuel TINYINT(1) NOT NULL, traveled_distance INT NOT NULL, showed_consumption INT NOT NULL, INDEX IDX_E251872F545317D1 (vehicle_id), INDEX IDX_E251872F6A70FE35 (fuel_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE partial_fueling ADD CONSTRAINT FK_E251872F545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('ALTER TABLE partial_fueling ADD CONSTRAINT FK_E251872F6A70FE35 FOREIGN KEY (fuel_type_id) REFERENCES fuel_type (id)');
        $this->addSql('ALTER TABLE fueling ADD from_partial TINYINT(1) NOT NULL DEFAULT 0');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE partial_fueling');
        $this->addSql('ALTER TABLE fueling DROP from_partial');
    }
}
