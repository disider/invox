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

class WorkingNoteRepository extends AbstractRepository
{
    const ROOT_ALIAS = 'workingNote';

    const FILTER_BY_MANAGER = 'filter_by_manager';
    const FILTER_BY_SALES_AGENT = 'filter_by_sales_manager';

    protected function getRootAlias()
    {
        return self::ROOT_ALIAS;
    }

    /** @return QueryBuilder */
    public function findAllQuery(array $filters = [], $pageIndex = 0, $pageSize = PHP_INT_MAX)
    {
        $qb = parent::findAllQuery($filters, $pageIndex, $pageSize);

        $qb->leftJoin('workingNote.company', 'company');

        return $this->applyFilters($qb, $filters);
    }

    private function filterByManager(QueryBuilder $qb, User $user)
    {
        $qb->leftJoin('company.managers', 'manager')
            ->andWhere('company.owner = :user')
            ->orWhere('manager = :user')
            ->setParameter('user', $user);

        return $qb;
    }


    private function filterBySalesAgent(QueryBuilder $qb, User $salesAgent)
    {
        $qb->leftJoin('company.salesAgents', 'salesAgents')
            ->orWhere('salesAgents = :salesAgent')
            ->setParameter('salesAgent', $salesAgent->getId());
        ;
        return $qb;
    }


    protected function applyFilters(QueryBuilder $qb, array $filters)
    {
        if (array_key_exists(self::FILTER_BY_MANAGER, $filters)) {
            $qb = $this->filterByManager($qb, $filters[self::FILTER_BY_MANAGER]);
        }

        if (array_key_exists(self::FILTER_BY_SALES_AGENT, $filters)) {
            $qb = $this->filterBySalesAgent($qb, $filters[self::FILTER_BY_SALES_AGENT]);
        }

        return $qb;
    }
}
