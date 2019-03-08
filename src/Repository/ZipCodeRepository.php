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

use App\Entity\ZipCode;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ZipCodeRepository extends AbstractRepository
{
    const ROOT_ALIAS = 'zipCode';

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ZipCode::class);
    }

    protected function getRootAlias()
    {
        return self::ROOT_ALIAS;
    }

    public function search($term)
    {
        return $this->createQueryBuilder('z')
            ->leftJoin('z.city', 'c')
            ->where('c.name like :term')
            ->setParameter('term', '%' . $term . '%')
            ->getQuery()
            ->execute();
    }
}
