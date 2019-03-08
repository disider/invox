<?php

namespace DoctrineMigrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160711145716 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE document_row DROP INDEX UNIQ_579A043FD240BD1D, ADD INDEX IDX_579A043FD240BD1D (linked_product_id)');
        $this->addSql('ALTER TABLE document_row DROP INDEX UNIQ_579A043F7A9872A1, ADD INDEX IDX_579A043F7A9872A1 (linked_service_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE document_row DROP INDEX IDX_579A043FD240BD1D, ADD UNIQUE INDEX UNIQ_579A043FD240BD1D (linked_product_id)');
        $this->addSql('ALTER TABLE document_row DROP INDEX IDX_579A043F7A9872A1, ADD UNIQUE INDEX UNIQ_579A043F7A9872A1 (linked_service_id)');
    }
}
