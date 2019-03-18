<?php

namespace DoctrineMigrations;

use App\Helper\DatabaseHelper;
use App\Migration\SqlMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170119091119 extends SqlMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $helper = new DatabaseHelper($this->getDbConnection());

        $records = $helper->fetchRows('company');
        foreach ($records as $record) {
            $helper->update(
                'company',
                [
                    'document_types' => 'a:6:{i:0;s:5:"quote";i:1;s:7:"invoice";i:2;s:5:"order";i:3;s:11:"credit_note";i:4;s:7:"receipt";i:5;s:13:"delivery_note";}',
                ],
                [
                    'id' => $record['id'],
                ]
            );
        }
    }
}
