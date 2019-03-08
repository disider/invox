<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

abstract class SqlMigration extends AbstractMigration implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function down(Schema $schema): void
    {
        throw new \LogicException('Cannot migrate down from this point on');
    }

    protected function executeQuery($sql)
    {
        $connection = $this->getDbConnection();
        $query = $connection->prepare($sql);
        $query->execute();

        return $query;
    }

    protected function executeUpdate($sql, array $params = [], array $types = [])
    {
        $connection = $this->getDbConnection();

        return $connection->executeUpdate($sql, $params, $types);
    }

    protected function getDbConnection()
    {
        $em = $this->container->get('doctrine')->getManager();
        $connection = $em->getConnection();

        return $connection;
    }
}
