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

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @JMS\ExclusionPolicy("all")
 */
abstract class Uploadable
{
    const UPLOAD_ROOT_DIR = __DIR__.'/../../../web/';

    /**
     * @Assert\Valid
     *
     * @var ArrayCollection
     */
    protected $attachments;

    /**
     * @Assert\File(maxSize="2M")
     * @var UploadedFile
     */
    protected $file;

    abstract public function getUploadDir();

    abstract public function getAttachmentsUploadDir();

    abstract protected function buildAttachment();

    public function __construct()
    {
        $this->attachments = new ArrayCollection();
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;

        if ($this->file) {
            $filename = sha1(uniqid(mt_rand(), true));

            /** @var Attachment $attachment */
            $attachment = $this->buildAttachment();
            $attachment->setFile($this->file);
            $attachment->setFileName($this->file->getClientOriginalName());
            $attachment->setFileUrl($filename.'.'.$this->file->guessExtension());

            $this->addAttachment($attachment);
        }
    }

    public function onUpload()
    {
        /** @var Attachment $attachment */
        foreach ($this->attachments as $attachment) {
            $attachment->moveFile();
        }
    }

    public function getAttachments()
    {
        return $this->attachments;
    }

    public function addAttachment(Attachment $attachment)
    {
        $this->attachments->add($attachment);
        $attachment->setUploadable($this);
    }

    public function removeAttachment(Attachment $attachment)
    {
        $this->attachments->removeElement($attachment);
    }

    public function getAttachmentsUploadRootDir()
    {
        return self::UPLOAD_ROOT_DIR.$this->getAttachmentsUploadDir();
    }

    public function getUploadRootDir()
    {
        return self::UPLOAD_ROOT_DIR.$this->getUploadDir();
    }

}