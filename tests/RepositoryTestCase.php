<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Tests\App;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class RepositoryTestCase extends ServiceTestCase
{
    /** @var EntityManager */
    protected $entityManager;

    /** @var EventDispatcherInterface */
    protected $dispatcher;

    public function setUp()
    {
        parent::setUp();

        $this->dispatcher = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');

        $this->entityManager = $this->getService('doctrine')->getManager();
        $this->purgeDatabase();

        $this->entityManager->clear();
    }

    protected function generateSchema()
    {
        $metadatas = $this->getMetadatas();

        if (!empty($metadatas)) {
            /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
            $tool = new SchemaTool($this->entityManager);
            $tool->dropSchema($metadatas);
            $tool->createSchema($metadatas);
        }
    }

    /**
     * @return array
     */
    protected function getMetadatas()
    {
        return $this->entityManager->getMetadataFactory()->getAllMetadata();
    }

    protected function purgeDatabase()
    {
        $purger = new ORMPurger($this->entityManager);
        $purger->purge();
    }
}
