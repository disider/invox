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
use App\Entity\Medium;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

class MediumRepository extends AbstractRepository
{
    const ROOT_ALIAS = 'medium';

    const FILTER_BY_MIME_TYPE = 'filter_by_mime_type';
    const FILTER_BY_COMPANY = 'filter_by_company';

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Medium::class);
    }

    protected function getRootAlias()
    {
        return self::ROOT_ALIAS;
    }

    /** @return QueryBuilder */
    public function findAllQuery(array $filters = [], $pageIndex = 0, $pageSize = PHP_INT_MAX)
    {
        $qb = parent::findAllQuery($filters, $pageIndex, $pageSize);

        if (array_key_exists(self::FILTER_BY_MIME_TYPE, $filters)) {
            $qb = $this->filterByMimeType($qb, $filters[self::FILTER_BY_MIME_TYPE]);
        }

        if (array_key_exists(self::FILTER_BY_COMPANY, $filters)) {
            $qb = $this->filterByCompany($qb, $filters[self::FILTER_BY_COMPANY]);
        }

        return $qb;
    }

    private function filterByMimeType(QueryBuilder $qb, $mimeTypes)
    {
        foreach ($mimeTypes as $i => $mimeType) {
            $qb
                ->orWhere(sprintf('(medium.fileUrl LIKE :%s)', 'mimeType' . $i))
                ->setParameter('mimeType' . $i, '%.' . $mimeType);
        }

        return $qb;
    }

    protected function filterByCompany(QueryBuilder $qb, Company $company)
    {
        $qb
            ->andWhere($this->getRootAlias() . '.container = :company')
            ->setParameter('company', $company);

        return $qb;
    }
}
