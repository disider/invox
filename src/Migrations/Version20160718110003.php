<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160718110003 extends AbstractMigration
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

        $this->addSql(
            'CREATE TABLE product_tag (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_E3A6E39C4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE service_tag (id INT AUTO_INCREMENT NOT NULL, service_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_21D9C4F4ED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql(
            'ALTER TABLE product_tag ADD CONSTRAINT FK_E3A6E39C4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE'
        );
        $this->addSql(
            'ALTER TABLE service_tag ADD CONSTRAINT FK_21D9C4F4ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE'
        );
        $this->addSql('ALTER TABLE document CHANGE discount_percent discount_percent TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE document_row CHANGE discount_percent discount_percent TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE product DROP category');
        $this->addSql('ALTER TABLE service DROP category');
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

        $this->addSql('DROP TABLE product_tag');
        $this->addSql('DROP TABLE service_tag');
        $this->addSql(
            'ALTER TABLE document CHANGE discount_percent discount_percent TINYINT(1) DEFAULT \'0\' NOT NULL'
        );
        $this->addSql(
            'ALTER TABLE document_row CHANGE discount_percent discount_percent TINYINT(1) DEFAULT \'0\' NOT NULL'
        );
        $this->addSql('ALTER TABLE product ADD category VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE service ADD category VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
