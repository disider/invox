<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160713130932 extends AbstractMigration
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
            'ALTER TABLE product CHANGE unit_price unit_price NUMERIC(16, 2) DEFAULT NULL, CHANGE initial_stock initial_stock NUMERIC(16, 2) NOT NULL, CHANGE current_stock current_stock NUMERIC(16, 2) NOT NULL'
        );
        $this->addSql('ALTER TABLE service CHANGE unit_price unit_price NUMERIC(16, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE tax_rate CHANGE amount amount NUMERIC(16, 2) NOT NULL');
        $this->addSql(
            'ALTER TABLE warehouse_record CHANGE load_quantity load_quantity NUMERIC(16, 2) DEFAULT NULL, CHANGE unload_quantity unload_quantity NUMERIC(16, 2) DEFAULT NULL, CHANGE purchase_price purchase_price NUMERIC(16, 2) DEFAULT NULL, CHANGE sell_price sell_price NUMERIC(16, 2) DEFAULT NULL'
        );
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

        $this->addSql(
            'ALTER TABLE product CHANGE unit_price unit_price NUMERIC(10, 0) DEFAULT NULL, CHANGE initial_stock initial_stock NUMERIC(10, 0) NOT NULL, CHANGE current_stock current_stock NUMERIC(10, 0) NOT NULL'
        );
        $this->addSql('ALTER TABLE service CHANGE unit_price unit_price NUMERIC(10, 0) DEFAULT NULL');
        $this->addSql('ALTER TABLE tax_rate CHANGE amount amount NUMERIC(10, 0) NOT NULL');
        $this->addSql(
            'ALTER TABLE warehouse_record CHANGE load_quantity load_quantity NUMERIC(10, 0) DEFAULT NULL, CHANGE unload_quantity unload_quantity NUMERIC(10, 0) DEFAULT NULL, CHANGE purchase_price purchase_price NUMERIC(10, 0) DEFAULT NULL, CHANGE sell_price sell_price NUMERIC(10, 0) DEFAULT NULL'
        );
    }
}
