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

use App\Entity\Company;
use App\Entity\Service;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ServiceRepository extends AbstractRepository
{
    const ROOT_ALIAS = 'service';

    const FILTER_BY_MANAGER = 'filter_by_manager';

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Service::class);
    }

    protected function getRootAlias()
    {
        return self::ROOT_ALIAS;
    }

    /** @return QueryBuilder */
    public function findAllQuery(array $filters = [], $pageIndex = 0, $pageSize = PHP_INT_MAX)
    {
        $qb = parent::findAllQuery($filters, $pageIndex, $pageSize);

        return $this->applyFilters($qb, $filters);
    }

    private function filterByManager(QueryBuilder $qb, User $user)
    {
        $qb
            ->leftJoin('service.company', 'company')
            ->leftJoin('company.managers', 'manager')
            ->andWhere('company.owner = :user')
            ->orWhere('manager = :user')
            ->setParameter('user', $user);

        return $qb;
    }

    public function search($term, Company $company, array $filters = [])
    {
        $qb = $this->createQueryBuilder('service')
            ->where('(service.code LIKE :term OR service.name LIKE :term)')
            ->andWhere('service.company = :company')
            ->setParameter('term', '%'.$term.'%')
            ->setParameter('company', $company);

        $qb = $this->applyFilters($qb, $filters);

        return $qb
            ->getQuery()
            ->execute();
    }

    protected function applyFilters(QueryBuilder $qb, array $filters)
    {
        if (array_key_exists(self::FILTER_BY_MANAGER, $filters)) {
            $qb = $this->filterByManager($qb, $filters[self::FILTER_BY_MANAGER]);
        }

        return $qb;
    }

}
