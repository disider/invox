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

use AppBundle\Entity\Page;
use Symfony\Bridge\Doctrine\RegistryInterface;

class PageRepository extends AbstractRepository
{
    const ROOT_ALIAS = 'page';

    const FILTER_BY_MIME_TYPE = 'filter_by_mime_type';
    const FILTER_BY_COMPANY = 'filter_by_company';

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Page::class);
    }

    protected function getRootAlias()
    {
        return self::ROOT_ALIAS;
    }
}
