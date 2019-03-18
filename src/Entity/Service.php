<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Entity;

use JMS\Serializer\Annotation as JMS;

class Service extends Item
{
    public static function create(Company $company, $name, $code)
    {
        $entity = new self();
        $entity->company = $company;
        $entity->name = $name;
        $entity->code = $code;

        return $entity;
    }

    public function getAttachmentsUploadDir()
    {
        return $this->getUploadDir().sprintf('/services/%s/attachments', $this->getId());
    }

    protected function buildAttachment()
    {
        return new ServiceAttachment();
    }

    /**
     * @JMS\VirtualProperty
     * @JMS\SerializedName("type")
     *
     * @return string
     */
    public function serializeType()
    {
        return 'service';
    }

    protected function buildTag()
    {
        return new ServiceTag();
    }
}
