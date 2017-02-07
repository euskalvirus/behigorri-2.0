<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170207163045 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE SensitiveData CHANGE isFile hasFile TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE User ADD decryptPassword VARCHAR(255) NOT NULL');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE SensitiveData CHANGE hasFile isFile TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE User DROP decryptPassword');
    }
}
