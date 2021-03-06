<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Entity\Repository;

class EntityRepository extends AbstractRepository
{
    const ROOT_ALIAS = 'entity';

    protected function getRootAlias()
    {
        return self::ROOT_ALIAS;
    }
}
