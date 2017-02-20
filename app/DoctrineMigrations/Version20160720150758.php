<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160720150758 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE paragraph DROP FOREIGN KEY FK_7DD39862A977936C');
        $this->addSql('ALTER TABLE paragraph DROP FOREIGN KEY FK_7DD39862727ACA70');
        $this->addSql('DROP INDEX IDX_7DD39862A977936C ON paragraph');
        $this->addSql('ALTER TABLE paragraph DROP tree_root, DROP lft, DROP rgt, DROP lvl');
        $this->addSql('ALTER TABLE paragraph ADD CONSTRAINT FK_7DD39862727ACA70 FOREIGN KEY (parent_id) REFERENCES paragraph (id) ON DELETE SET NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE paragraph DROP FOREIGN KEY FK_7DD39862727ACA70');
        $this->addSql('ALTER TABLE paragraph ADD tree_root INT DEFAULT NULL, ADD lft INT NOT NULL, ADD rgt INT NOT NULL, ADD lvl INT NOT NULL');
        $this->addSql('ALTER TABLE paragraph ADD CONSTRAINT FK_7DD39862A977936C FOREIGN KEY (tree_root) REFERENCES paragraph (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE paragraph ADD CONSTRAINT FK_7DD39862727ACA70 FOREIGN KEY (parent_id) REFERENCES paragraph (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_7DD39862A977936C ON paragraph (tree_root)');
    }
}
