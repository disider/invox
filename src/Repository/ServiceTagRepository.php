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

use App\Entity\ServiceTag;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ServiceTagRepository extends AbstractTagRepository
{
    const ROOT_ALIAS = 'serviceTag';

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ServiceTag::class);
    }

    protected function getRootAlias()
    {
        return self::ROOT_ALIAS;
    }
}
