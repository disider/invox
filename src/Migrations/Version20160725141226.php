<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160725141226 extends AbstractMigration
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
            'CREATE TABLE paragraph_template (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, company_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_1AC75B42727ACA70 (parent_id), INDEX IDX_1AC75B42979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql(
            'ALTER TABLE paragraph_template ADD CONSTRAINT FK_1AC75B42727ACA70 FOREIGN KEY (parent_id) REFERENCES paragraph_template (id) ON DELETE SET NULL'
        );
        $this->addSql(
            'ALTER TABLE paragraph_template ADD CONSTRAINT FK_1AC75B42979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE SET NULL'
        );
        $this->addSql('DROP INDEX title_idx ON paragraph');
        $this->addSql('ALTER TABLE paragraph CHANGE title title VARCHAR(255) NOT NULL');
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

        $this->addSql('ALTER TABLE paragraph_template DROP FOREIGN KEY FK_1AC75B42727ACA70');
        $this->addSql('DROP TABLE paragraph_template');
        $this->addSql('ALTER TABLE paragraph CHANGE title title VARCHAR(128) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('CREATE INDEX title_idx ON paragraph (title)');
    }
}
