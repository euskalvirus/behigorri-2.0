<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151203184539 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE User (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, createdAt VARCHAR(300) DEFAULT NULL, updatedAt VARCHAR(300) DEFAULT NULL, UNIQUE INDEX UNIQ_2DA17977E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE UserGroup (user_id INT UNSIGNED NOT NULL, group_id INT UNSIGNED NOT NULL, INDEX IDX_954D5B0A76ED395 (user_id), INDEX IDX_954D5B0FE54D947 (group_id), PRIMARY KEY(user_id, group_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE SensitiveData (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, createdAt VARCHAR(300) DEFAULT NULL, updatedAt VARCHAR(300) DEFAULT NULL, ownerId INT UNSIGNED DEFAULT NULL, INDEX IDX_72C1B183E05EFD25 (ownerId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE SensitiveDataTag (sensitivedata_id INT UNSIGNED NOT NULL, tag_id INT UNSIGNED NOT NULL, INDEX IDX_C90A39174C0D4FA5 (sensitivedata_id), INDEX IDX_C90A3917BAD26311 (tag_id), PRIMARY KEY(sensitivedata_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE SensitiveDataGroup (sensitivedata_id INT UNSIGNED NOT NULL, group_id INT UNSIGNED NOT NULL, INDEX IDX_72ED6F7F4C0D4FA5 (sensitivedata_id), INDEX IDX_72ED6F7FFE54D947 (group_id), PRIMARY KEY(sensitivedata_id, group_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Tag (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, createdAt VARCHAR(300) DEFAULT NULL, updatedAt VARCHAR(300) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `Group` (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(300) DEFAULT NULL, createdAt VARCHAR(300) DEFAULT NULL, updatedAt VARCHAR(300) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE UserGroup ADD CONSTRAINT FK_954D5B0A76ED395 FOREIGN KEY (user_id) REFERENCES User (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE UserGroup ADD CONSTRAINT FK_954D5B0FE54D947 FOREIGN KEY (group_id) REFERENCES `Group` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE SensitiveData ADD CONSTRAINT FK_72C1B183E05EFD25 FOREIGN KEY (ownerId) REFERENCES User (id)');
        $this->addSql('ALTER TABLE SensitiveDataTag ADD CONSTRAINT FK_C90A39174C0D4FA5 FOREIGN KEY (sensitivedata_id) REFERENCES SensitiveData (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE SensitiveDataTag ADD CONSTRAINT FK_C90A3917BAD26311 FOREIGN KEY (tag_id) REFERENCES Tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE SensitiveDataGroup ADD CONSTRAINT FK_72ED6F7F4C0D4FA5 FOREIGN KEY (sensitivedata_id) REFERENCES SensitiveData (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE SensitiveDataGroup ADD CONSTRAINT FK_72ED6F7FFE54D947 FOREIGN KEY (group_id) REFERENCES `Group` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE User ADD token VARCHAR(355) DEFAULT NULL, ADD god TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE User ADD salt VARCHAR(355) DEFAULT NULL, ADD userActive TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('INSERT INTO User (name,email, password, createdAt, updatedAt,god,userActive) VALUES ("admin","admin@admin.com", "$2a$04$eUClLowFkXBaMsn4n0m/yOv07nsu4pStf2gQgS4bPOotMA2ehiW7C",'. date("Y-m-d H:i:s") .'","'. date("Y-m-d H:i:s") .'",true,true)');
        
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE UserGroup DROP FOREIGN KEY FK_954D5B0A76ED395');
        $this->addSql('ALTER TABLE SensitiveData DROP FOREIGN KEY FK_72C1B183E05EFD25');
        $this->addSql('ALTER TABLE SensitiveDataTag DROP FOREIGN KEY FK_C90A39174C0D4FA5');
        $this->addSql('ALTER TABLE SensitiveDataGroup DROP FOREIGN KEY FK_72ED6F7F4C0D4FA5');
        $this->addSql('ALTER TABLE SensitiveDataTag DROP FOREIGN KEY FK_C90A3917BAD26311');
        $this->addSql('ALTER TABLE UserGroup DROP FOREIGN KEY FK_954D5B0FE54D947');
        $this->addSql('ALTER TABLE SensitiveDataGroup DROP FOREIGN KEY FK_72ED6F7FFE54D947');
        $this->addSql('DROP TABLE User');
        $this->addSql('DROP TABLE UserGroup');
        $this->addSql('DROP TABLE SensitiveData');
        $this->addSql('DROP TABLE SensitiveDataTag');
        $this->addSql('DROP TABLE SensitiveDataGroup');
        $this->addSql('DROP TABLE Tag');
        $this->addSql('DROP TABLE `Group`');
    }
}
