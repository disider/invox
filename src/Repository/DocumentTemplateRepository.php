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

use App\Entity\DocumentTemplate;
use Symfony\Bridge\Doctrine\RegistryInterface;

class DocumentTemplateRepository extends AbstractRepository
{
    const ROOT_ALIAS = 'template';

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DocumentTemplate::class);
    }

    protected function getRootAlias()
    {
        return self::ROOT_ALIAS;
    }
}
