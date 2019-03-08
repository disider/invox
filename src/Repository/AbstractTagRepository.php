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

abstract class AbstractTagRepository extends AbstractRepository
{
    public function search($term, Company $company)
    {
        $alias = $this->getRootAlias();

        $qb = $this->createQueryBuilder($alias)
            ->leftJoin($alias . '.taggable', 'taggable')
            ->where($alias . '.name LIKE :term')
            ->andWhere('taggable.company = :company')
            ->setParameter('term', '%' . $term . '%')
            ->setParameter('company', $company)
            ->distinct()
            ->groupBy($alias . '.name');

        return $qb
            ->getQuery()
            ->getResult();
    }

}
