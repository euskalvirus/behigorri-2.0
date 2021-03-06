<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170313120819 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `Group` CHANGE salt salt VARBINARY(32) NOT NULL');
        $this->addSql('ALTER TABLE SensitiveData CHANGE fileName fileName VARCHAR(255) DEFAULT NULL, CHANGE fileExtension fileExtension VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE User CHANGE salt salt VARBINARY(32) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `Group` CHANGE salt salt VARBINARY(8000) DEFAULT NULL');
        $this->addSql('ALTER TABLE SensitiveData CHANGE fileName fileName VARCHAR(255) NOT NULL COLLATE utf8_general_ci, CHANGE fileExtension fileExtension VARCHAR(255) NOT NULL COLLATE utf8_general_ci');
        $this->addSql('ALTER TABLE User CHANGE salt salt VARBINARY(8000) NOT NULL');
    }
}
