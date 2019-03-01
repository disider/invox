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

use AppBundle\Entity\ProductTag;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ProductTagRepository extends AbstractTagRepository
{
    const ROOT_ALIAS = 'productTag';

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProductTag::class);
    }

    protected function getRootAlias()
    {
        return self::ROOT_ALIAS;
    }
}
