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

class PettyCashNoteRepository extends ProtocolRepository
{
    const ROOT_ALIAS = 'note';

    const FILTER_BY_MANAGER = 'filter_by_manager';
    const FILTER_BY_ACCOUNTANT = 'filter_by_accountant';
    const FILTER_BY_COMPANY = 'filter_by_company';

    public function getTotalAmount(QueryBuilder $query)
    {
        $aliases = $query->getAllAliases();

        if(!array_search('invoicePerNote', $aliases)) {
            $query->leftJoin('note.invoices', 'invoicePerNote');
        }

        return $query
            ->select('SUM(invoicePerNote.amount)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    protected function getRootAlias()
    {
        return self::ROOT_ALIAS;
    }

    /** @return QueryBuilder */
    protected function filterByYear(QueryBuilder $qb, $alias, $year)
    {
        return $qb->andWhere($alias . '.recordedAt BETWEEN :first AND :last')
            ->setParameter('first', new \DateTime(sprintf('%s-01-01', $year)))
            ->setParameter('last', new \DateTime(sprintf('%s-12-31', $year)));
    }
}
