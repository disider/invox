<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160719165930 extends AbstractMigration
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

        $this->addSql('ALTER TABLE working_note ADD company_id INT NOT NULL');
        $this->addSql(
            'ALTER TABLE working_note ADD CONSTRAINT FK_91C748EE979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE'
        );
        $this->addSql('CREATE INDEX IDX_91C748EE979B1AD6 ON working_note (company_id)');
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

        $this->addSql('ALTER TABLE working_note DROP FOREIGN KEY FK_91C748EE979B1AD6');
        $this->addSql('DROP INDEX IDX_91C748EE979B1AD6 ON working_note');
        $this->addSql('ALTER TABLE working_note DROP company_id');
    }
}
