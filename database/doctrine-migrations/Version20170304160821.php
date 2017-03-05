<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170304160821 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
     public function up(Schema $schema)
     {
         // this up() migration is auto-generated, please modify it to your needs
         $this->addSql('ALTER TABLE User CHANGE salt salt BINARY(32) DEFAULT NULL');
     }

     /**
      * @param Schema $schema
      */
     public function down(Schema $schema)
     {
         // this down() migration is auto-generated, please modify it to your needs
         $this->addSql('ALTER TABLE User CHANGE salt salt VARCHAR(355) DEFAULT NULL');

     }
}
