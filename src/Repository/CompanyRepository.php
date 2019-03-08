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
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CompanyRepository extends AbstractRepository
{
    const ROOT_ALIAS = 'company';

    const FILTER_BY_OWNER = 'filter_by_owner';
    const FILTER_BY_ACCOUNTANT = 'filter_by_accountant';
    const FILTER_BY_SALES_AGENT = 'filter_by_sales_agent';

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Company::class);
    }

    protected function getRootAlias()
    {
        return self::ROOT_ALIAS;
    }

    /** @return QueryBuilder */
    public function findAllQuery(array $filters = [], $pageIndex = 0, $pageSize = PHP_INT_MAX)
    {
        $qb = parent::findAllQuery($filters, $pageIndex, $pageSize);

        if (array_key_exists(self::FILTER_BY_OWNER, $filters)) {
            $qb = $this->filterByOwner($qb, $filters[self::FILTER_BY_OWNER]);
        }

        if (array_key_exists(self::FILTER_BY_ACCOUNTANT, $filters)) {
            $qb = $this->filterByAccountant($qb, $filters[self::FILTER_BY_ACCOUNTANT]);
        }

        if (array_key_exists(self::FILTER_BY_SALES_AGENT, $filters)) {
            $qb = $this->filterBySalesAgent($qb, $filters[self::FILTER_BY_SALES_AGENT]);
        }

        return $qb;
    }

    private function filterBySalesAgent(QueryBuilder $qb, User $salesAgent)
    {
        $qb->leftJoin('company.salesAgents', 'salesAgents')
            ->orWhere('salesAgents = :salesAgent')
            ->setParameter('salesAgent', $salesAgent->getId());

        return $qb;
    }

    private function filterByOwner(QueryBuilder $qb, User $owner)
    {
        $qb->andWhere('company.owner = :owner')
            ->setParameter('owner', $owner);

        return $qb;
    }

    private function filterByAccountant(QueryBuilder $qb, User $accountant)
    {
        $qb
            ->orWhere('company.accountant = :accountant')
            ->setParameter('accountant', $accountant);

        return $qb;
    }

}
