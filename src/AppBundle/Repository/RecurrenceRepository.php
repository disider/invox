<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Repository;

use AppBundle\Entity\Company;
use AppBundle\Entity\Recurrence;
use AppBundle\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RecurrenceRepository extends AbstractRepository
{
    const ROOT_ALIAS = 'recurrence';

    const FILTER_BY_MANAGER = 'filter_by_manager';
    const FILTER_BY_COMPANY = 'filter_by_company';

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Recurrence::class);
    }

    protected function getRootAlias()
    {
        return self::ROOT_ALIAS;
    }

    /** @return QueryBuilder */
    public function findAllQuery(array $filters = [], $pageIndex = 0, $pageSize = PHP_INT_MAX)
    {
        $qb = parent::findAllQuery($filters, $pageIndex, $pageSize);

        $qb->leftJoin($this->getRootAlias() . '.company', 'company');

        return $this->applyFilters($qb, $filters);
    }

    public function findOneByIdAndCompany($recurrenceId, Company $company)
    {
        return $this->createQueryBuilder('r')
            ->where('r.id = :recurrenceId')
            ->andWhere('r.company = :company')
            ->setParameter('recurrenceId', $recurrenceId)
            ->setParameter('company', $company)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function search($term, $customerId)
    {
        return $this->createQueryBuilder('r')
            ->where('r.content like :term')
            ->leftJoin('r.customer', 'c')
            ->andWhere('c.id = :customer')
            ->andWhere('r.nextDueDate IS NOT NULL')
            ->setParameter('term', '%' . $term . '%')
            ->setParameter('customer', $customerId)
            ->getQuery()
            ->execute();
    }

    protected function applyFilters(QueryBuilder $qb, array $filters)
    {
        if (array_key_exists(self::FILTER_BY_COMPANY, $filters)) {
            $qb = $this->filterByCompany($qb, $filters[self::FILTER_BY_COMPANY]);
        }

        if (array_key_exists(self::FILTER_BY_MANAGER, $filters)) {
            $qb = $this->filterByManager($qb, $filters[self::FILTER_BY_MANAGER]);
        }

        return $qb;
    }

    private function filterByCompany(QueryBuilder $qb, Company $company)
    {
        $qb->andWhere('company = :company')
            ->setParameter('company', $company);

        return $qb;
    }

    private function filterByManager(QueryBuilder $qb, User $user)
    {
        $qb->leftJoin('company.managers', 'manager')
            ->andWhere('company.owner = :user OR manager = :user')
            ->setParameter('user', $user);

        return $qb;
    }
}
