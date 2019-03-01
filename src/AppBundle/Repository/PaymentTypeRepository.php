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

use AppBundle\Entity\PaymentType;
use Symfony\Bridge\Doctrine\RegistryInterface;

class PaymentTypeRepository extends AbstractRepository
{
    const ROOT_ALIAS = 'paymentType';

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PaymentType::class);
    }

    protected function getRootAlias()
    {
        return self::ROOT_ALIAS;
    }
}
