<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

abstract class AbstractRepository extends EntityRepository implements AbstractRepositoryInterface, ServiceEntityRepositoryInterface
{
    abstract protected function getRootAlias();

    public function __construct(ManagerRegistry $registry, $entityClass)
    {
        $manager = $registry->getManagerForClass($entityClass);

        if ($manager === null) {
            throw new \LogicException(sprintf(
                'Could not find the entity manager for class "%s". Check your Doctrine configuration to make sure it is configured to load this entity’s metadata.',
                $entityClass
            ));
        }

        parent::__construct($manager, $manager->getClassMetadata($entityClass));
    }

    public function findLast()
    {
        $alias = $this->getRootAlias();

        return $this->createQueryBuilder($alias)
            ->orderBy($alias . '.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();
    }

    /** @return QueryBuilder */
    public function findAllQuery(array $filters = [], $pageIndex = 0, $pageSize = PHP_INT_MAX)
    {
        return $this->createQueryBuilder($this->getRootAlias());
    }

    public function findOneById($id)
    {
        $alias = $this->getRootAlias();

        $qb = $this->createQueryBuilder($alias)
            ->where($alias . '.id = :id')
            ->setParameter('id', $id);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function findAll(array $filters = [], $pageIndex = 0, $pageSize = PHP_INT_MAX)
    {
        $qb = $this->findAllQuery($filters, $pageIndex, $pageSize);

        return $qb->getQuery()->execute();
    }

    public function countAll(array $filters = [])
    {
        $qb = $this->findAllQuery($filters);

        return $qb->select(sprintf('COUNT(%s.id)', $this->getRootAlias()))
            ->getQuery()->getSingleScalarResult();
    }

    public function save($object)
    {
        $em = $this->getEntityManager();
        $em->persist($object);
        $em->flush();

        return $object;
    }

    public function delete($object)
    {
        $em = $this->getEntityManager();
        $em->remove($object);
        $em->flush();
    }
}
