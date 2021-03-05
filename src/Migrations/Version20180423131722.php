<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180423131722 extends AbstractMigration
{
    /**
     * Initialise fuel type list
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->addSql('INSERT INTO fuel_type(`name`) VALUES 
                              (\'SP95\'),
                              (\'SP98\'),
                              (\'SP95-E10\'),
                              (\'Diesel\'),
                              (\'GPL\'),
                              (\'E85\')');
    }

    /**
     * Clear fuel type table
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM fuel_type WHERE 1 = 1');
    }
}
