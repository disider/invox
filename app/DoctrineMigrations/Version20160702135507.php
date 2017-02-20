<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160702135507 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE customer_attachment (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, file_name VARCHAR(255) NOT NULL, file_url VARCHAR(255) NOT NULL, INDEX IDX_8496DD399395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_attachment (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, file_name VARCHAR(255) NOT NULL, file_url VARCHAR(255) NOT NULL, INDEX IDX_EA3886904584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service_attachment (id INT AUTO_INCREMENT NOT NULL, service_id INT NOT NULL, file_name VARCHAR(255) NOT NULL, file_url VARCHAR(255) NOT NULL, INDEX IDX_EF0EE00FED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE customer_attachment ADD CONSTRAINT FK_8496DD399395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_attachment ADD CONSTRAINT FK_EA3886904584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE service_attachment ADD CONSTRAINT FK_EF0EE00FED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document_attachment DROP FOREIGN KEY FK_D89C72ED48B9001A');
        $this->addSql('DROP INDEX IDX_D89C72ED48B9001A ON document_attachment');
        $this->addSql('ALTER TABLE document_attachment CHANGE uploadable_id document_id INT NOT NULL');
        $this->addSql('ALTER TABLE document_attachment ADD CONSTRAINT FK_D89C72EDC33F7837 FOREIGN KEY (document_id) REFERENCES document (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_D89C72EDC33F7837 ON document_attachment (document_id)');
        $this->addSql('ALTER TABLE petty_cash_note_attachment DROP FOREIGN KEY FK_67FF295F48B9001A');
        $this->addSql('DROP INDEX IDX_67FF295F48B9001A ON petty_cash_note_attachment');
        $this->addSql('ALTER TABLE petty_cash_note_attachment CHANGE uploadable_id petty_cash_note_id INT NOT NULL');
        $this->addSql('ALTER TABLE petty_cash_note_attachment ADD CONSTRAINT FK_67FF295FC758D2F1 FOREIGN KEY (petty_cash_note_id) REFERENCES petty_cash_note (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_67FF295FC758D2F1 ON petty_cash_note_attachment (petty_cash_note_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE customer_attachment');
        $this->addSql('DROP TABLE product_attachment');
        $this->addSql('DROP TABLE service_attachment');
        $this->addSql('ALTER TABLE document_attachment DROP FOREIGN KEY FK_D89C72EDC33F7837');
        $this->addSql('DROP INDEX IDX_D89C72EDC33F7837 ON document_attachment');
        $this->addSql('ALTER TABLE document_attachment CHANGE document_id uploadable_id INT NOT NULL');
        $this->addSql('ALTER TABLE document_attachment ADD CONSTRAINT FK_D89C72ED48B9001A FOREIGN KEY (uploadable_id) REFERENCES document (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_D89C72ED48B9001A ON document_attachment (uploadable_id)');
        $this->addSql('ALTER TABLE petty_cash_note_attachment DROP FOREIGN KEY FK_67FF295FC758D2F1');
        $this->addSql('DROP INDEX IDX_67FF295FC758D2F1 ON petty_cash_note_attachment');
        $this->addSql('ALTER TABLE petty_cash_note_attachment CHANGE petty_cash_note_id uploadable_id INT NOT NULL');
        $this->addSql('ALTER TABLE petty_cash_note_attachment ADD CONSTRAINT FK_67FF295F48B9001A FOREIGN KEY (uploadable_id) REFERENCES petty_cash_note (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_67FF295F48B9001A ON petty_cash_note_attachment (uploadable_id)');
    }
}
