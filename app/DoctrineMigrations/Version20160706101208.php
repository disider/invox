<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160706101208 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE document_row ADD linked_product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE document_row ADD CONSTRAINT FK_579A043FD240BD1D FOREIGN KEY (linked_product_id) REFERENCES product (id) ON DELETE SET NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_579A043FD240BD1D ON document_row (linked_product_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE document_row DROP FOREIGN KEY FK_579A043FD240BD1D');
        $this->addSql('DROP INDEX UNIQ_579A043FD240BD1D ON document_row');
        $this->addSql('ALTER TABLE document_row DROP linked_product_id');
    }
}
