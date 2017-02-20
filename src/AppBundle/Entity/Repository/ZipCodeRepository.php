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

class ZipCodeRepository extends AbstractRepository
{
    const ROOT_ALIAS = 'zipCode';

    public function search($term)
    {
        return $this->createQueryBuilder('z')
            ->leftJoin('z.city', 'c')
            ->where('c.name like :term')
            ->setParameter('term', '%' . $term . '%')
            ->getQuery()
            ->execute();
    }

    protected function getRootAlias()
    {
        return self::ROOT_ALIAS;
    }
}
