<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160603151354 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE account (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, type VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, initial_amount NUMERIC(16, 2) NOT NULL, current_amount NUMERIC(16, 2) NOT NULL, iban LONGTEXT DEFAULT NULL, INDEX IDX_7D3656A4979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, province_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_2D5B0234E946114A (province_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, accountant_id INT DEFAULT NULL, owner_id INT NOT NULL, country_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, vat_number VARCHAR(20) NOT NULL, fiscal_code VARCHAR(20) DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, fax_number VARCHAR(255) DEFAULT NULL, address LONGTEXT DEFAULT NULL, zip_code VARCHAR(10) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, province VARCHAR(5) DEFAULT NULL, address_notes LONGTEXT DEFAULT NULL, logo_url VARCHAR(255) DEFAULT NULL, modules LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_4FBF094F9582AA74 (accountant_id), INDEX IDX_4FBF094F7E3C61F9 (owner_id), INDEX IDX_4FBF094FF92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company_manager (company_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_DA763F6E979B1AD6 (company_id), INDEX IDX_DA763F6EA76ED395 (user_id), PRIMARY KEY(company_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(5) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, country_id INT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, vat_number VARCHAR(20) DEFAULT NULL, fiscal_code VARCHAR(20) DEFAULT NULL, referent VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, fax_number VARCHAR(255) DEFAULT NULL, address LONGTEXT DEFAULT NULL, zip_code VARCHAR(10) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, province VARCHAR(5) DEFAULT NULL, address_notes LONGTEXT DEFAULT NULL, notes LONGTEXT DEFAULT NULL, language VARCHAR(5) NOT NULL, INDEX IDX_81398E09979B1AD6 (company_id), INDEX IDX_81398E09F92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, linked_invoice_id INT DEFAULT NULL, linked_credit_note_id INT DEFAULT NULL, document_template_id INT NOT NULL, company_id INT NOT NULL, company_country_id INT NOT NULL, linked_customer_id INT DEFAULT NULL, linked_document_id INT DEFAULT NULL, customer_country_id INT NOT NULL, payment_type_id INT DEFAULT NULL, ref VARCHAR(255) NOT NULL, internal_ref VARCHAR(255) DEFAULT NULL, type VARCHAR(255) NOT NULL, direction VARCHAR(10) NOT NULL, title VARCHAR(255) DEFAULT NULL, content LONGTEXT DEFAULT NULL, company_name VARCHAR(255) DEFAULT NULL, company_vat_number VARCHAR(20) DEFAULT NULL, company_fiscal_code VARCHAR(20) DEFAULT NULL, company_phone_number VARCHAR(255) DEFAULT NULL, company_fax_number VARCHAR(255) DEFAULT NULL, company_address LONGTEXT DEFAULT NULL, company_zip_code VARCHAR(10) DEFAULT NULL, company_city VARCHAR(255) DEFAULT NULL, company_province VARCHAR(5) DEFAULT NULL, company_address_notes LONGTEXT DEFAULT NULL, company_logo_url VARCHAR(255) DEFAULT NULL, customer_name VARCHAR(255) DEFAULT NULL, customer_vat_number VARCHAR(20) DEFAULT NULL, customer_fiscal_code VARCHAR(20) DEFAULT NULL, customer_phone_number VARCHAR(20) DEFAULT NULL, customer_fax_number VARCHAR(20) DEFAULT NULL, customer_address VARCHAR(255) DEFAULT NULL, customer_address_notes LONGTEXT DEFAULT NULL, customer_zip_code VARCHAR(10) DEFAULT NULL, customer_city VARCHAR(255) DEFAULT NULL, customer_province VARCHAR(5) DEFAULT NULL, year INT NOT NULL, issued_at DATETIME NOT NULL, valid_until DATETIME DEFAULT NULL, net_total NUMERIC(16, 2) NOT NULL, taxes NUMERIC(16, 2) NOT NULL, gross_total NUMERIC(16, 2) NOT NULL, discount NUMERIC(16, 2) NOT NULL, rounding NUMERIC(16, 2) NOT NULL, notes LONGTEXT DEFAULT NULL, showTotals VARCHAR(32) NOT NULL, language VARCHAR(5) NOT NULL, status VARCHAR(255) DEFAULT NULL, self_invoice TINYINT(1) NOT NULL, cost_center VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_D8698A76BE4D2ABA (linked_invoice_id), UNIQUE INDEX UNIQ_D8698A765A8BC182 (linked_credit_note_id), INDEX IDX_D8698A76877338D2 (document_template_id), INDEX IDX_D8698A76979B1AD6 (company_id), INDEX IDX_D8698A7644A1D7E6 (company_country_id), INDEX IDX_D8698A767BBAD31B (linked_customer_id), INDEX IDX_D8698A762B1068DF (linked_document_id), INDEX IDX_D8698A764E63AF2 (customer_country_id), INDEX IDX_D8698A76DC058279 (payment_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document_attachment (id INT AUTO_INCREMENT NOT NULL, uploadable_id INT NOT NULL, file_name VARCHAR(255) DEFAULT NULL, file_url VARCHAR(255) DEFAULT NULL, INDEX IDX_D89C72ED48B9001A (uploadable_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document_row (id INT AUTO_INCREMENT NOT NULL, tax_rate_id INT NOT NULL, document_id INT NOT NULL, position INT NOT NULL, title VARCHAR(255) NOT NULL, unit_price NUMERIC(16, 2) NOT NULL, quantity NUMERIC(16, 2) NOT NULL, discount NUMERIC(16, 2) NOT NULL, net_cost NUMERIC(16, 2) NOT NULL, taxes NUMERIC(16, 2) NOT NULL, gross_cost NUMERIC(16, 2) NOT NULL, INDEX IDX_579A043FFDD13F95 (tax_rate_id), INDEX IDX_579A043FC33F7837 (document_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document_template (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, text_color VARCHAR(255) NOT NULL, heading_color VARCHAR(255) NOT NULL, table_header_color VARCHAR(255) NOT NULL, table_header_background_color VARCHAR(255) NOT NULL, font_family VARCHAR(255) NOT NULL, header LONGTEXT NOT NULL, content LONGTEXT NOT NULL, footer LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document_template_per_company (id INT AUTO_INCREMENT NOT NULL, document_template_id INT NOT NULL, company_id INT NOT NULL, name VARCHAR(255) NOT NULL, text_color VARCHAR(255) NOT NULL, heading_color VARCHAR(255) NOT NULL, table_header_color VARCHAR(255) NOT NULL, table_header_background_color VARCHAR(255) NOT NULL, font_family VARCHAR(255) NOT NULL, header LONGTEXT NOT NULL, content LONGTEXT NOT NULL, footer LONGTEXT NOT NULL, INDEX IDX_EE1C9677877338D2 (document_template_id), INDEX IDX_EE1C9677979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invite (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, sender_id INT NOT NULL, receiver_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, token VARCHAR(255) NOT NULL, INDEX IDX_C7E210D7979B1AD6 (company_id), INDEX IDX_C7E210D7F624B39D (sender_id), INDEX IDX_C7E210D7CD53EDB6 (receiver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invoice_per_note (id INT AUTO_INCREMENT NOT NULL, invoice_id INT NOT NULL, note_id INT NOT NULL, amount NUMERIC(16, 2) NOT NULL, INDEX IDX_1D84A63B2989F1FD (invoice_id), INDEX IDX_1D84A63B26ED0855 (note_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE log (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, action VARCHAR(255) NOT NULL, details LONGTEXT NOT NULL, date DATETIME NOT NULL, INDEX IDX_8F3F68C5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE page (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE page_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_A3D51B1D2C2AC5D3 (translatable_id), UNIQUE INDEX page_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_type (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, days INT DEFAULT NULL, end_of_month TINYINT(1) NOT NULL, position INT NOT NULL, INDEX IDX_AD5DC05D979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_type_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_B0A8E8402C2AC5D3 (translatable_id), UNIQUE INDEX payment_type_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE petty_cash_note (id INT AUTO_INCREMENT NOT NULL, account_from_id INT DEFAULT NULL, account_to_id INT DEFAULT NULL, company_id INT NOT NULL, ref VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, amount NUMERIC(16, 2) NOT NULL, recorded_at DATE NOT NULL, INDEX IDX_5DB202F2B1E5CD43 (account_from_id), INDEX IDX_5DB202F26BA9314 (account_to_id), INDEX IDX_5DB202F2979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE petty_cash_note_attachment (id INT AUTO_INCREMENT NOT NULL, uploadable_id INT NOT NULL, file_name VARCHAR(255) DEFAULT NULL, file_url VARCHAR(255) DEFAULT NULL, INDEX IDX_67FF295F48B9001A (uploadable_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, tax_rate_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, unit_price NUMERIC(10, 0) DEFAULT NULL, measure_unit VARCHAR(255) DEFAULT NULL, category VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, internal_notes LONGTEXT DEFAULT NULL, enabled_in_warehouse TINYINT(1) NOT NULL, initial_stock NUMERIC(10, 0) NOT NULL, current_stock NUMERIC(10, 0) NOT NULL, INDEX IDX_D34A04AD979B1AD6 (company_id), INDEX IDX_D34A04ADFDD13F95 (tax_rate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE province (id INT AUTO_INCREMENT NOT NULL, country_id INT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(5) NOT NULL, INDEX IDX_4ADAD40BF92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, tax_rate_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, unit_price NUMERIC(10, 0) DEFAULT NULL, measure_unit VARCHAR(255) DEFAULT NULL, category VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, internal_notes LONGTEXT DEFAULT NULL, INDEX IDX_E19D9AD2979B1AD6 (company_id), INDEX IDX_E19D9AD2FDD13F95 (tax_rate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tax_rate (id INT AUTO_INCREMENT NOT NULL, position INT NOT NULL, amount NUMERIC(10, 0) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tax_rate_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_B225A80C2C2AC5D3 (translatable_id), UNIQUE INDEX tax_rate_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, reset_password_token VARCHAR(255) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE warehouse_record (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, load_quantity NUMERIC(10, 0) DEFAULT NULL, unload_quantity NUMERIC(10, 0) DEFAULT NULL, purchase_price NUMERIC(10, 0) DEFAULT NULL, sell_price NUMERIC(10, 0) DEFAULT NULL, date DATE NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_7E6040E04584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE zip_code (id INT AUTO_INCREMENT NOT NULL, city_id INT NOT NULL, code VARCHAR(10) NOT NULL, INDEX IDX_A1ACE1588BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE account ADD CONSTRAINT FK_7D3656A4979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE city ADD CONSTRAINT FK_2D5B0234E946114A FOREIGN KEY (province_id) REFERENCES province (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F9582AA74 FOREIGN KEY (accountant_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE company_manager ADD CONSTRAINT FK_DA763F6E979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE company_manager ADD CONSTRAINT FK_DA763F6EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E09979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E09F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76BE4D2ABA FOREIGN KEY (linked_invoice_id) REFERENCES document (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A765A8BC182 FOREIGN KEY (linked_credit_note_id) REFERENCES document (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76877338D2 FOREIGN KEY (document_template_id) REFERENCES document_template_per_company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A7644A1D7E6 FOREIGN KEY (company_country_id) REFERENCES country (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A767BBAD31B FOREIGN KEY (linked_customer_id) REFERENCES customer (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A762B1068DF FOREIGN KEY (linked_document_id) REFERENCES document (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A764E63AF2 FOREIGN KEY (customer_country_id) REFERENCES country (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76DC058279 FOREIGN KEY (payment_type_id) REFERENCES payment_type (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE document_attachment ADD CONSTRAINT FK_D89C72ED48B9001A FOREIGN KEY (uploadable_id) REFERENCES document (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document_row ADD CONSTRAINT FK_579A043FFDD13F95 FOREIGN KEY (tax_rate_id) REFERENCES tax_rate (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document_row ADD CONSTRAINT FK_579A043FC33F7837 FOREIGN KEY (document_id) REFERENCES document (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document_template_per_company ADD CONSTRAINT FK_EE1C9677877338D2 FOREIGN KEY (document_template_id) REFERENCES document_template (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document_template_per_company ADD CONSTRAINT FK_EE1C9677979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE invite ADD CONSTRAINT FK_C7E210D7979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE invite ADD CONSTRAINT FK_C7E210D7F624B39D FOREIGN KEY (sender_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE invite ADD CONSTRAINT FK_C7E210D7CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE invoice_per_note ADD CONSTRAINT FK_1D84A63B2989F1FD FOREIGN KEY (invoice_id) REFERENCES document (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE invoice_per_note ADD CONSTRAINT FK_1D84A63B26ED0855 FOREIGN KEY (note_id) REFERENCES petty_cash_note (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE log ADD CONSTRAINT FK_8F3F68C5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE page_translation ADD CONSTRAINT FK_A3D51B1D2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES page (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE payment_type ADD CONSTRAINT FK_AD5DC05D979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE payment_type_translation ADD CONSTRAINT FK_B0A8E8402C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES payment_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE petty_cash_note ADD CONSTRAINT FK_5DB202F2B1E5CD43 FOREIGN KEY (account_from_id) REFERENCES account (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE petty_cash_note ADD CONSTRAINT FK_5DB202F26BA9314 FOREIGN KEY (account_to_id) REFERENCES account (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE petty_cash_note ADD CONSTRAINT FK_5DB202F2979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE petty_cash_note_attachment ADD CONSTRAINT FK_67FF295F48B9001A FOREIGN KEY (uploadable_id) REFERENCES petty_cash_note (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADFDD13F95 FOREIGN KEY (tax_rate_id) REFERENCES tax_rate (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE province ADD CONSTRAINT FK_4ADAD40BF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2FDD13F95 FOREIGN KEY (tax_rate_id) REFERENCES tax_rate (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE tax_rate_translation ADD CONSTRAINT FK_B225A80C2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES tax_rate (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE warehouse_record ADD CONSTRAINT FK_7E6040E04584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE zip_code ADD CONSTRAINT FK_A1ACE1588BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE petty_cash_note DROP FOREIGN KEY FK_5DB202F2B1E5CD43');
        $this->addSql('ALTER TABLE petty_cash_note DROP FOREIGN KEY FK_5DB202F26BA9314');
        $this->addSql('ALTER TABLE zip_code DROP FOREIGN KEY FK_A1ACE1588BAC62AF');
        $this->addSql('ALTER TABLE account DROP FOREIGN KEY FK_7D3656A4979B1AD6');
        $this->addSql('ALTER TABLE company_manager DROP FOREIGN KEY FK_DA763F6E979B1AD6');
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E09979B1AD6');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A76979B1AD6');
        $this->addSql('ALTER TABLE document_template_per_company DROP FOREIGN KEY FK_EE1C9677979B1AD6');
        $this->addSql('ALTER TABLE invite DROP FOREIGN KEY FK_C7E210D7979B1AD6');
        $this->addSql('ALTER TABLE payment_type DROP FOREIGN KEY FK_AD5DC05D979B1AD6');
        $this->addSql('ALTER TABLE petty_cash_note DROP FOREIGN KEY FK_5DB202F2979B1AD6');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD979B1AD6');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2979B1AD6');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094FF92F3E70');
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E09F92F3E70');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A7644A1D7E6');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A764E63AF2');
        $this->addSql('ALTER TABLE province DROP FOREIGN KEY FK_4ADAD40BF92F3E70');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A767BBAD31B');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A76BE4D2ABA');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A765A8BC182');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A762B1068DF');
        $this->addSql('ALTER TABLE document_attachment DROP FOREIGN KEY FK_D89C72ED48B9001A');
        $this->addSql('ALTER TABLE document_row DROP FOREIGN KEY FK_579A043FC33F7837');
        $this->addSql('ALTER TABLE invoice_per_note DROP FOREIGN KEY FK_1D84A63B2989F1FD');
        $this->addSql('ALTER TABLE document_template_per_company DROP FOREIGN KEY FK_EE1C9677877338D2');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A76877338D2');
        $this->addSql('ALTER TABLE page_translation DROP FOREIGN KEY FK_A3D51B1D2C2AC5D3');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A76DC058279');
        $this->addSql('ALTER TABLE payment_type_translation DROP FOREIGN KEY FK_B0A8E8402C2AC5D3');
        $this->addSql('ALTER TABLE invoice_per_note DROP FOREIGN KEY FK_1D84A63B26ED0855');
        $this->addSql('ALTER TABLE petty_cash_note_attachment DROP FOREIGN KEY FK_67FF295F48B9001A');
        $this->addSql('ALTER TABLE warehouse_record DROP FOREIGN KEY FK_7E6040E04584665A');
        $this->addSql('ALTER TABLE city DROP FOREIGN KEY FK_2D5B0234E946114A');
        $this->addSql('ALTER TABLE document_row DROP FOREIGN KEY FK_579A043FFDD13F95');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADFDD13F95');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2FDD13F95');
        $this->addSql('ALTER TABLE tax_rate_translation DROP FOREIGN KEY FK_B225A80C2C2AC5D3');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094F9582AA74');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094F7E3C61F9');
        $this->addSql('ALTER TABLE company_manager DROP FOREIGN KEY FK_DA763F6EA76ED395');
        $this->addSql('ALTER TABLE invite DROP FOREIGN KEY FK_C7E210D7F624B39D');
        $this->addSql('ALTER TABLE invite DROP FOREIGN KEY FK_C7E210D7CD53EDB6');
        $this->addSql('ALTER TABLE log DROP FOREIGN KEY FK_8F3F68C5A76ED395');
        $this->addSql('DROP TABLE account');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE company_manager');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE document_attachment');
        $this->addSql('DROP TABLE document_row');
        $this->addSql('DROP TABLE document_template');
        $this->addSql('DROP TABLE document_template_per_company');
        $this->addSql('DROP TABLE invite');
        $this->addSql('DROP TABLE invoice_per_note');
        $this->addSql('DROP TABLE log');
        $this->addSql('DROP TABLE page');
        $this->addSql('DROP TABLE page_translation');
        $this->addSql('DROP TABLE payment_type');
        $this->addSql('DROP TABLE payment_type_translation');
        $this->addSql('DROP TABLE petty_cash_note');
        $this->addSql('DROP TABLE petty_cash_note_attachment');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE province');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE tax_rate');
        $this->addSql('DROP TABLE tax_rate_translation');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE warehouse_record');
        $this->addSql('DROP TABLE zip_code');
    }
}
