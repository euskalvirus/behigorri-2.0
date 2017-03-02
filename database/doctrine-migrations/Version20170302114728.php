<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170302114728 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE SensitiveData ADD (groupId INT UNSIGNED DEFAULT NULL, INDEX IDX_72C22283EFFFFA86 (groupId))');
        $this->addSql('ALTER TABLE SensitiveData ADD CONSTRAINT FK_72C22283EFFFFA86 FOREIGN KEY (groupId) REFERENCES `Group` (id)');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE SensitiveData DROP FOREIGN KEY FK_72C22283EFFFFA86');
        $this->addSql('ALTER TABLE SensitiveData DROP groupId');

    }
}
