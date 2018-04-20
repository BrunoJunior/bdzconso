<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180420215807 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E486D46EFD15');
        $this->addSql('DROP INDEX IDX_1B80E486D46EFD15 ON vehicle');
        $this->addSql('ALTER TABLE vehicle CHANGE prefered_fuel_type_id preferred_fuel_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E4864D30C00D FOREIGN KEY (preferred_fuel_type_id) REFERENCES fuel_type (id)');
        $this->addSql('CREATE INDEX IDX_1B80E4864D30C00D ON vehicle (preferred_fuel_type_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E4864D30C00D');
        $this->addSql('DROP INDEX IDX_1B80E4864D30C00D ON vehicle');
        $this->addSql('ALTER TABLE vehicle CHANGE preferred_fuel_type_id prefered_fuel_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E486D46EFD15 FOREIGN KEY (prefered_fuel_type_id) REFERENCES fuel_type (id)');
        $this->addSql('CREATE INDEX IDX_1B80E486D46EFD15 ON vehicle (prefered_fuel_type_id)');
    }
}
