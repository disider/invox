<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Repository;

use AppBundle\Entity\Product;
use AppBundle\Entity\WarehouseRecord;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

class WarehouseRecordRepository extends AbstractRepository
{
    const ROOT_ALIAS = 'record';

    const FILTER_BY_PRODUCT = 'filter_by_manager';

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, WarehouseRecord::class);
    }

    protected function getRootAlias()
    {
        return self::ROOT_ALIAS;
    }

    /** @return QueryBuilder */
    public function findAllQuery(array $filters = [], $pageIndex = 0, $pageSize = PHP_INT_MAX)
    {
        $qb = parent::findAllQuery($filters, $pageIndex, $pageSize);

        if (array_key_exists(self::FILTER_BY_PRODUCT, $filters)) {
            $qb = $this->filterByProduct($qb, $filters[self::FILTER_BY_PRODUCT]);
        }

        return $qb;
    }

    private function filterByProduct(QueryBuilder $qb, Product $product)
    {
        $qb
            ->andWhere('record.product = :product')
            ->setParameter('product', $product);

        return $qb;
    }
}
