<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170303174918 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `Group` DROP password');
        $this->addSql('ALTER TABLE `Group` DROP publicKey');
        $this->addSql('ALTER TABLE `Group` DROP privateKey');
        $this->addSql('ALTER TABLE `Group` ADD (decryptPassword VARCHAR(255) NOT NULL, salt VARCHAR(355) NOT NULL)');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `Group` DROP decryptPassword, salt');
        $this->addSql('ALTER TABLE `Group` ADD (password VARCHAR(255) NOT NULL, publicKey VARCHAR(355) NOT NULL, privateKey VARCHAR(355) NOT NULL)');

    }
}
