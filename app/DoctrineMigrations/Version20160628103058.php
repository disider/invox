<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160628103058 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE medium (id INT AUTO_INCREMENT NOT NULL, container_id INT NOT NULL, file_name VARCHAR(255) NOT NULL, file_url VARCHAR(255) NOT NULL, INDEX IDX_C67345B7BC21F742 (container_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE medium ADD CONSTRAINT FK_C67345B7BC21F742 FOREIGN KEY (container_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document_attachment CHANGE file_name file_name VARCHAR(255) NOT NULL, CHANGE file_url file_url VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE petty_cash_note_attachment CHANGE file_name file_name VARCHAR(255) NOT NULL, CHANGE file_url file_url VARCHAR(255) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE medium');
        $this->addSql('ALTER TABLE document_attachment CHANGE file_name file_name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE file_url file_url VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE petty_cash_note_attachment CHANGE file_name file_name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE file_url file_url VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
