<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170313100111 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `Group` CHANGE salt salt VARCHAR(355) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AC016BC15E237E06 ON `Group` (name)');
        $this->addSql('ALTER TABLE SensitiveData DROP FOREIGN KEY FK_72C1B183E05EFD25');
        $this->addSql('ALTER TABLE SensitiveData DROP FOREIGN KEY FK_72C22283EFFFFA86');
        $this->addSql('ALTER TABLE SensitiveData CHANGE fileName fileName VARCHAR(255) NOT NULL, CHANGE fileExtension fileExtension VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE SensitiveData ADD CONSTRAINT FK_72C1B183E05EFD25 FOREIGN KEY (ownerId) REFERENCES User (id) ON DELETE SET NULL');
        $this->addSql('DROP INDEX idx_72c22283effffa86 ON SensitiveData');
        $this->addSql('CREATE INDEX IDX_72C1B183ED8188B0 ON SensitiveData (groupId)');
        $this->addSql('ALTER TABLE SensitiveData ADD CONSTRAINT FK_72C22283EFFFFA86 FOREIGN KEY (groupId) REFERENCES `Group` (id)');
        $this->addSql('ALTER TABLE User CHANGE salt salt VARCHAR(355) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3BC4F1635E237E06 ON Tag (name)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_AC016BC15E237E06 ON `Group`');
        $this->addSql('ALTER TABLE `Group` CHANGE salt salt BINARY(32) NOT NULL');
        $this->addSql('ALTER TABLE SensitiveData DROP FOREIGN KEY FK_72C1B183E05EFD25');
        $this->addSql('ALTER TABLE SensitiveData DROP FOREIGN KEY FK_72C1B183ED8188B0');
        $this->addSql('ALTER TABLE SensitiveData CHANGE fileName fileName VARCHAR(255) DEFAULT NULL COLLATE utf8_general_ci, CHANGE fileExtension fileExtension VARCHAR(255) DEFAULT NULL COLLATE utf8_general_ci');
        $this->addSql('ALTER TABLE SensitiveData ADD CONSTRAINT FK_72C1B183E05EFD25 FOREIGN KEY (ownerId) REFERENCES User (id)');
        $this->addSql('DROP INDEX idx_72c1b183ed8188b0 ON SensitiveData');
        $this->addSql('CREATE INDEX IDX_72C22283EFFFFA86 ON SensitiveData (groupId)');
        $this->addSql('ALTER TABLE SensitiveData ADD CONSTRAINT FK_72C1B183ED8188B0 FOREIGN KEY (groupId) REFERENCES `Group` (id)');
        $this->addSql('DROP INDEX UNIQ_3BC4F1635E237E06 ON Tag');
        $this->addSql('ALTER TABLE User CHANGE salt salt BINARY(32) DEFAULT NULL');
    }
}
