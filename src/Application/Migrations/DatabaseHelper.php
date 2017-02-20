<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Application\Migrations;

use Doctrine\DBAL\Connection;

class DatabaseHelper
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function fetchRow($table, $where = '')
    {
        $sql = sprintf('SELECT * FROM %s %s', $table, $where);

        $stmt = $this->connection->executeQuery($sql);
        $actual = $stmt->fetch();
        return $actual;
    }

    public function fetchRows($table, $where = '')
    {
        $sql = sprintf('SELECT * FROM %s %s', $table, $where);

        $stmt = $this->connection->executeQuery($sql);
        return $stmt->fetchAll();
    }

    public function insert($table, $data)
    {
        $this->connection->insert($table, $data);

        return $this->connection->lastInsertId();
    }

    public function update($table, $data, $where = [])
    {
        $this->connection->update($table, $data, $where);
    }

    public function delete($table, $where)
    {
        $this->connection->delete($table, $where);
    }

}
