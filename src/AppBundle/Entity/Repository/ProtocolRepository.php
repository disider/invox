<?php
/**
 * This file is part of the Invox.
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

abstract class ProtocolRepository extends SelectedCompanyRepository
{
    public function findLastProtocolNumber(Company $company, $year, $filters = [])
    {
        $alias = $this->getRootAlias();

        $qb = $this->createQueryBuilder($alias)
            ->select($alias . '.ref as ref')
            ->addSelect('ABS(' . $alias . '.ref) AS HIDDEN refNumber')
            ->where($alias . '.company = :company')
            ->setParameter('company', $company)
            ->orderBy('refNumber', 'desc')
            ->setMaxResults(1)
        ;

        $qb = $this->filterByYear($qb, $alias, $year);
        $qb = $this->applyFilters($qb, $filters);

        $result = $qb->getQuery()->getScalarResult();

        if(count($result) > 0) {
            return $result[0]['ref'];
        }

        return 0;
    }

    protected abstract function filterByYear(QueryBuilder $qb, $alias, $year);
}
