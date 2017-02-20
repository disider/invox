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

class TagRepository extends AbstractRepository
{
    const ROOT_ALIAS = 'tag';

    protected function getRootAlias()
    {
        return self::ROOT_ALIAS;
    }

    public function search($term, Company $company)
    {
        $qb = $this->createQueryBuilder('tag')
            ->leftJoin('tag.taggable', 'taggable')
            ->where('tag.name LIKE :term')
            ->andWhere('taggable.company = :company')
            ->setParameter('term', '%' . $term . '%')
            ->setParameter('company', $company)
            ->distinct()
            ->groupBy('tag.name')
        ;

        return $qb
            ->getQuery()
            ->getResult();
    }

}
