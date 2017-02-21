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

class CityRepository extends AbstractRepository
{
    const ROOT_ALIAS = 'city';

    public function search($term)
    {
        return $this->createQueryBuilder('c')
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
