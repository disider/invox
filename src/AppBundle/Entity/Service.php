<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

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
        return $this->getUploadDir() . sprintf('/services/%s/attachments', $this->getId());
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
