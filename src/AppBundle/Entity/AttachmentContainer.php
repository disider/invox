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

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/**
 * @JMS\ExclusionPolicy("all")
 */
abstract class AttachmentContainer
{
    const UPLOAD_ROOT_DIR = __DIR__ . '/../../../web/';

    /**
     * @Assert\Valid
     *
     * @var ArrayCollection
     */
    protected $attachments;

    abstract public function getUploadDir();

    abstract public function getAttachmentsUploadDir();

    abstract protected function buildAttachment();

    public function __construct()
    {
        $this->attachments = new ArrayCollection();
    }

    public function getAttachments()
    {
        return $this->attachments;
    }

    public function hasAttachments()
    {
        return $this->attachments->count() > 0;
    }

    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;
    }

    public function addAttachment(Attachable $attachment)
    {
        $this->attachments->add($attachment);

        $attachment->setContainer($this);
    }

    public function removeAttachment(Attachable $attachment)
    {
        $this->attachments->removeElement($attachment);

        $attachment->setContainer(null);
    }

    public function getAttachmentsUploadRootDir()
    {
        return self::UPLOAD_ROOT_DIR . $this->getAttachmentsUploadDir();
    }

    public function getUploadRootDir()
    {
        return self::UPLOAD_ROOT_DIR . $this->getUploadDir();
    }

}