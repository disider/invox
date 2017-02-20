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

use Doctrine\ORM\QueryBuilder;

class UserRepository extends AbstractRepository
{
    const ROOT_ALIAS = 'user';
    const FILTER_BY_COMPANIES = 'filter_by_companies';

    protected function getRootAlias()
    {
        return self::ROOT_ALIAS;
    }

    /** @return QueryBuilder */
    public function findAllQuery(array $filters = [], $pageIndex = 0, $pageSize = PHP_INT_MAX)
    {
        $qb = parent::findAllQuery($filters, $pageIndex, $pageSize);

        if (array_key_exists(self::FILTER_BY_COMPANIES, $filters)) {
            $qb = $this->filterByCompanies($qb, $filters[self::FILTER_BY_COMPANIES]);
        }

        return $qb;
    }

    private function filterByCompanies(QueryBuilder $qb, $companies)
    {
        $ids = [];
        foreach ($companies as $company) {
            $ids[] = $company->getId();
        }

        $qb
            ->leftJoin('user.ownedCompanies', 'ownedCompany')
            ->leftJoin('user.managedCompanies', 'managedCompany')
            ->andWhere('(ownedCompany IN (:companies) OR managedCompany IN (:companies))')
            ->setParameter('companies', $ids);

        return $qb;
    }
}
