<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180504202350 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fuel_type ADD color VARCHAR(255) NOT NULL');
        $this->addSql('UPDATE fuel_type SET color=\'#0F0\' WHERE name=\'SP95\'');
        $this->addSql('UPDATE fuel_type SET color=\'#FF0\' WHERE name=\'Diesel\'');
        $this->addSql('UPDATE fuel_type SET color=\'#000\' WHERE name=\'GPL\'');
        $this->addSql('UPDATE fuel_type SET color=\'#00F\' WHERE name=\'E85\'');
        $this->addSql('UPDATE fuel_type SET color=\'#32CD32\' WHERE name=\'SP98\'');
        $this->addSql('UPDATE fuel_type SET color=\'#080\' WHERE name=\'SP95-E10\'');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fuel_type DROP color');
    }
}
