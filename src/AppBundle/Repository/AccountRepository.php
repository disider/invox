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

use AppBundle\Entity\Account;
use AppBundle\Entity\Company;
use Symfony\Bridge\Doctrine\RegistryInterface;

class AccountRepository extends SelectedCompanyRepository
{
    const ROOT_ALIAS = 'account';

    const FILTER_BY_MANAGER = 'filter_by_manager';
    const FILTER_BY_COMPANY = 'filter_by_company';

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Account::class);
    }

    protected function getRootAlias()
    {
        return self::ROOT_ALIAS;
    }

    public function findByCompanyQuery(Company $company)
    {
        return $this->findAllQuery([self::FILTER_BY_COMPANY => $company]);
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
