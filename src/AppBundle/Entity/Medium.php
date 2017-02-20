<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Entity;

class Medium extends Attachable
{
    public function getUploaderType()
    {
        return 'medium';
    }

    public static function create($fileName, $fileUrl, Company $company)
    {
        $entity = new self();
        $entity->fileName = $fileName;
        $entity->fileUrl = $fileUrl;
        $entity->container = $company;

        return $entity;
    }
}
