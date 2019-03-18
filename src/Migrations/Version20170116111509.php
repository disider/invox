<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170116111509 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql(
            'CREATE TABLE recurrence (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, customer_id INT DEFAULT NULL, direction VARCHAR(10) NOT NULL, content LONGTEXT DEFAULT NULL, start_at DATETIME NOT NULL, end_at DATETIME DEFAULT NULL, repeats VARCHAR(20) NOT NULL, repeat_every INT NOT NULL, repeat_on VARCHAR(255) DEFAULT NULL, occurrences INT DEFAULT NULL, INDEX IDX_1FB7F221979B1AD6 (company_id), INDEX IDX_1FB7F2219395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql(
            'ALTER TABLE recurrence ADD CONSTRAINT FK_1FB7F221979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE'
        );
        $this->addSql(
            'ALTER TABLE recurrence ADD CONSTRAINT FK_1FB7F2219395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) ON DELETE SET NULL'
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('DROP TABLE recurrence');
    }
}
