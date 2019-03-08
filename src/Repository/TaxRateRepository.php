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

use App\Entity\TaxRate;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TaxRateRepository extends AbstractRepository
{
    const ROOT_ALIAS = 'taxRate';

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TaxRate::class);
    }

    protected function getRootAlias()
    {
        return self::ROOT_ALIAS;
    }
}
