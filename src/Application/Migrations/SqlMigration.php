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
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Statement;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
abstract class SqlMigration extends AbstractMigration implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        throw new \LogicException('Cannot migrate down from this point on');
    }

    /** @return Statement */
    protected function executeQuery($sql)
    {
        $connection = $this->getDbConnection();
        $query = $connection->prepare($sql);
        $query->execute();

        return $query;
    }

    /** @return Statement */
    protected function executeUpdate($sql, array $params = [], array $types = [])
    {
        $connection = $this->getDbConnection();

        return $connection->executeUpdate($sql, $params, $types);
    }

    /**
     * @return Connection
     */
    protected function getDbConnection()
    {
        $em = $this->container->get('doctrine')->getManager();
        $connection = $em->getConnection();
        return $connection;
    }
}
