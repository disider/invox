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
use Symfony\Bridge\Doctrine\RegistryInterface;

class CompanyRepository extends AbstractRepository
{
    const ROOT_ALIAS = 'company';

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Company::class);
    }

    protected function getRootAlias()
    {
        return self::ROOT_ALIAS;
    }
}
