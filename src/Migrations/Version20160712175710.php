<?php

namespace DoctrineMigrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160712175710 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE payment_type_per_company (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, days INT DEFAULT NULL, end_of_month TINYINT(1) NOT NULL, position INT NOT NULL, INDEX IDX_262A5D0C979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_type_per_company_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_F56042032C2AC5D3 (translatable_id), UNIQUE INDEX payment_type_per_company_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE payment_type_per_company ADD CONSTRAINT FK_262A5D0C979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE payment_type_per_company_translation ADD CONSTRAINT FK_F56042032C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES payment_type_per_company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE payment_type DROP FOREIGN KEY FK_AD5DC05D979B1AD6');
        $this->addSql('DROP INDEX IDX_AD5DC05D979B1AD6 ON payment_type');
        $this->addSql('ALTER TABLE payment_type DROP company_id');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE payment_type_per_company_translation DROP FOREIGN KEY FK_F56042032C2AC5D3');
        $this->addSql('DROP TABLE payment_type_per_company');
        $this->addSql('DROP TABLE payment_type_per_company_translation');
        $this->addSql('ALTER TABLE payment_type ADD company_id INT NOT NULL');
        $this->addSql('ALTER TABLE payment_type ADD CONSTRAINT FK_AD5DC05D979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_AD5DC05D979B1AD6 ON payment_type (company_id)');
    }
}
