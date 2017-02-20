<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160722143103 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE paragraph DROP FOREIGN KEY FK_7DD39862F89DAA15');
        $this->addSql('ALTER TABLE paragraph CHANGE working_note_id working_note_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE paragraph ADD CONSTRAINT FK_7DD39862F89DAA15 FOREIGN KEY (working_note_id) REFERENCES working_note (id) ON DELETE SET NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE paragraph DROP FOREIGN KEY FK_7DD39862F89DAA15');
        $this->addSql('ALTER TABLE paragraph CHANGE working_note_id working_note_id INT NOT NULL');
        $this->addSql('ALTER TABLE paragraph ADD CONSTRAINT FK_7DD39862F89DAA15 FOREIGN KEY (working_note_id) REFERENCES working_note (id) ON DELETE CASCADE');
    }
}
