<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170217135600 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE working_note ADD customer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE working_note ADD CONSTRAINT FK_91C748EE9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_91C748EE9395C3F3 ON working_note (customer_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE working_note DROP FOREIGN KEY FK_91C748EE9395C3F3');
        $this->addSql('DROP INDEX IDX_91C748EE9395C3F3 ON working_note');
        $this->addSql('ALTER TABLE working_note DROP customer_id');
    }
}
