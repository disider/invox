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

use AppBundle\Entity\Company;
use AppBundle\Entity\User;
use Doctrine\ORM\QueryBuilder;

abstract class SelectedCompanyRepository extends AbstractRepository
{
    const FILTER_BY_MANAGER = 'filter_by_manager';
    const FILTER_BY_ACCOUNTANT = 'filter_by_accountant';
    const FILTER_BY_COMPANY = 'filter_by_company';

    /** @return QueryBuilder */
    public function findAllQuery(array $filters = [], $pageIndex = 0, $pageSize = PHP_INT_MAX)
    {
        $qb = parent::findAllQuery($filters, $pageIndex, $pageSize);

        $qb = $this->applyFilters($qb, $filters);

        return $qb;
    }

    protected function applyFilters($qb, array $filters)
    {
        if (array_key_exists(self::FILTER_BY_MANAGER, $filters)) {
            $qb = $this->filterByManager($qb, $filters[self::FILTER_BY_MANAGER]);
        }

        if (array_key_exists(self::FILTER_BY_ACCOUNTANT, $filters)) {
            $qb = $this->filterByAccountant($qb, $filters[self::FILTER_BY_ACCOUNTANT]);
        }

        if (array_key_exists(self::FILTER_BY_COMPANY, $filters)) {
            $qb = $this->filterByCompany($qb, $filters[self::FILTER_BY_COMPANY]);
        }

        return $qb;
    }

    protected function filterByManager(QueryBuilder $qb, User $user)
    {
        $qb
            ->leftJoin($this->getRootAlias() . '.company', 'company')
            ->leftJoin('company.managers', 'manager')
            ->andWhere('company.owner = :user')
            ->orWhere('manager = :user')
            ->setParameter('user', $user);

        return $qb;
    }

    protected function filterBySales($qb, $FILTER_BY_SALES)
    {
        $qb
            ->leftJoin($this->getRootAlias() . '.company', 'company')
            ->leftJoin('company.managers', 'manager')
            ->andWhere('company.owner = :user')
            ->orWhere('manager = :user')
            ->setParameter('user', $user);

        return $qb;

    }


    protected function filterByAccountant(QueryBuilder $qb, User $user)
    {
        $qb
            ->leftJoin($this->getRootAlias() . '.company', 'company')
            ->andWhere('company.accountant = :user')
            ->setParameter('user', $user);

        return $qb;
    }

    protected function filterByCompany(QueryBuilder $qb, Company $company)
    {
        $qb
            ->andWhere($this->getRootAlias() . '.company = :company')
            ->setParameter('company', $company);

        return $qb;
    }
}
