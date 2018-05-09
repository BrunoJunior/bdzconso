<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180509220107 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE partial_fueling (id INT AUTO_INCREMENT NOT NULL, vehicle_id INT DEFAULT NULL, fuel_type_id INT DEFAULT NULL, date DATE NOT NULL, volume INT NOT NULL, volume_price INT NOT NULL, amount INT NOT NULL, additived_fuel TINYINT(1) NOT NULL, INDEX IDX_E251872F545317D1 (vehicle_id), INDEX IDX_E251872F6A70FE35 (fuel_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE partial_fueling ADD CONSTRAINT FK_E251872F545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('ALTER TABLE partial_fueling ADD CONSTRAINT FK_E251872F6A70FE35 FOREIGN KEY (fuel_type_id) REFERENCES fuel_type (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE partial_fueling');
    }
}
