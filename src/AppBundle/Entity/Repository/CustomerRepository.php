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

class CustomerRepository extends SelectedCompanyRepository
{
    const ROOT_ALIAS = 'customer';

    const FILTER_BY_MANAGER = 'filter_by_manager';

    public function generateNextCode(Company $company)
    {
        $codes = $this->createQueryBuilder('c')
            ->select('c.code')
            ->where('c.company = :company')
            ->orderBy('c.code', 'DESC')
            ->setParameter('company', $company)
            ->getQuery()
            ->getScalarResult()
        ;

        if(count($codes) > 0) {
            $code = $codes[0]['code'];
            return ++$code;
        }

        return 1;
    }

    public function findOneByIdAndCompany($customerId, Company $company)
    {
        return $this->createQueryBuilder('c')
            ->where('c.id = :customerId')
            ->andWhere('c.company = :company')
            ->setParameter('customerId', $customerId)
            ->setParameter('company', $company)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function search($term, Company $company)
    {
        return $this->createQueryBuilder('c')
            ->where('c.name like :term')
            ->andWhere('c.company = :company')
            ->setParameter('term', '%' . $term . '%')
            ->setParameter('company', $company)
            ->getQuery()
            ->execute();
    }

    protected function getRootAlias()
    {
        return self::ROOT_ALIAS;
    }

    protected function applyFilters($qb, array $filters)
    {
        if (array_key_exists(self::FILTER_BY_MANAGER, $filters)) {
            $qb = $this->filterByManager($qb, $filters[self::FILTER_BY_MANAGER]);
        }

        if (array_key_exists(self::FILTER_BY_COMPANY, $filters)) {
            $qb = $this->filterByCompany($qb, $filters[self::FILTER_BY_COMPANY]);
        }

        return $qb;
    }
}
