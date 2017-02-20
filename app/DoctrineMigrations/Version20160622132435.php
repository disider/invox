<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160622132435 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE paragraph (id INT AUTO_INCREMENT NOT NULL, tree_root INT DEFAULT NULL, parent_id INT DEFAULT NULL, working_note_id INT NOT NULL, title VARCHAR(128) NOT NULL, description LONGTEXT NOT NULL, lft INT NOT NULL, rgt INT NOT NULL, lvl INT NOT NULL, INDEX IDX_7DD39862A977936C (tree_root), INDEX IDX_7DD39862727ACA70 (parent_id), INDEX IDX_7DD39862F89DAA15 (working_note_id), INDEX title_idx (title), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE paragraph ADD CONSTRAINT FK_7DD39862A977936C FOREIGN KEY (tree_root) REFERENCES paragraph (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE paragraph ADD CONSTRAINT FK_7DD39862727ACA70 FOREIGN KEY (parent_id) REFERENCES paragraph (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE paragraph ADD CONSTRAINT FK_7DD39862F89DAA15 FOREIGN KEY (working_note_id) REFERENCES working_note (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE paragraph DROP FOREIGN KEY FK_7DD39862A977936C');
        $this->addSql('ALTER TABLE paragraph DROP FOREIGN KEY FK_7DD39862727ACA70');
        $this->addSql('DROP TABLE paragraph');
    }
}
