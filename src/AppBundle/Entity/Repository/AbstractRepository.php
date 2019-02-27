<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\ManagerRegistry;

abstract class AbstractRepository extends EntityRepository
{
    abstract protected function getRootAlias();

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
