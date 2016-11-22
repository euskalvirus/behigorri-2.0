<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161024232125 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE SensitiveData ADD isFile TINYINT(1) DEFAULT \'0\' NOT NULL');
    	
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
    	$this->addSql('ALTER TABLE SensitiveData DROP isFile');
    	 

    }
}