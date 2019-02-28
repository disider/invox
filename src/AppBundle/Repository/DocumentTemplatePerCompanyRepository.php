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

use AppBundle\Entity\Company;
use AppBundle\Entity\DocumentTemplatePerCompany;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

class DocumentTemplatePerCompanyRepository extends AbstractRepository
{
    const ROOT_ALIAS = 'template';

    const FILTER_BY_COMPANY = 'filter_by_company';

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DocumentTemplatePerCompany::class);
    }

    protected function getRootAlias()
    {
        return self::ROOT_ALIAS;
    }

    /** @return QueryBuilder */
    public function findAllQuery(array $filters = [], $pageIndex = 0, $pageSize = PHP_INT_MAX)
    {
        $qb = parent::findAllQuery($filters, $pageIndex, $pageSize);

        if (array_key_exists(self::FILTER_BY_COMPANY, $filters)) {
            $qb = $this->filterByCompany($qb, $filters[self::FILTER_BY_COMPANY]);
        }

        return $qb;
    }

    private function filterByCompany(QueryBuilder $qb, Company $company)
    {
        $qb
            ->andWhere('template.company = :company')
            ->setParameter('company', $company);

        return $qb;
    }
}
