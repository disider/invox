<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160706125456 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE document_row ADD linked_service_id INT DEFAULT NULL');
        $this->addSql(
            'ALTER TABLE document_row ADD CONSTRAINT FK_579A043F7A9872A1 FOREIGN KEY (linked_service_id) REFERENCES service (id) ON DELETE SET NULL'
        );
        $this->addSql('CREATE UNIQUE INDEX UNIQ_579A043F7A9872A1 ON document_row (linked_service_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE document_row DROP FOREIGN KEY FK_579A043F7A9872A1');
        $this->addSql('DROP INDEX UNIQ_579A043F7A9872A1 ON document_row');
        $this->addSql('ALTER TABLE document_row DROP linked_service_id');
    }
}
