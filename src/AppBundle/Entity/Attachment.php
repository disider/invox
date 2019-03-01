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

use Symfony\Component\HttpFoundation\File\UploadedFile;

class Attachment
{
    /** @var int */
    protected $id;

    /** @var UploadedFile */
    protected $file;

    /** @var string */
    protected $fileName;

    /** @var string */
    protected $fileUrl;

    /** @var Uploadable */
    protected $uploadable;

    /** @var boolean */
    protected $deleted;

    public function __toString()
    {
        return $this->fileName;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file)
    {
        $this->file = $file;
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    public function getFileUrl()
    {
        return $this->fileUrl;
    }

    public function setFileUrl($fileUrl)
    {
        $this->fileUrl = $fileUrl;
    }

    public function getUploadable()
    {
        return $this->uploadable;
    }

    public function setUploadable($uploadable)
    {
        $this->uploadable = $uploadable;
    }

    public function isDeleted()
    {
        return $this->deleted;
    }

    public function setDeleted($deleted)
    {
        if ($deleted) {
            $this->deleted = $deleted;
            $this->fileName = null;
            $this->getUploadable()->removeAttachment($this);
        }
    }

    public function onRemove()
    {
        @unlink($this->getUploadRootDir() . '/' . $this->fileUrl);
    }

    public function getWebPath()
    {
        return null == $this->fileUrl
            ? ""
            : $this->getUploadDir() . '/' . $this->fileUrl;
    }

    public function moveFile()
    {
        if ($this->file) {
            $this->file->move($this->getUploadRootDir(), $this->fileUrl);
            $this->file = null;
        }
    }

    private function getUploadDir()
    {
        return $this->getUploadable()->getAttachmentsUploadDir();
    }

    private function getUploadRootDir()
    {
        return $this->getUploadable()->getAttachmentsUploadRootDir();
    }

    public function copyFile()
    {
        $ext = pathinfo($this->fileUrl, PATHINFO_EXTENSION);

        $newFileUrl = sha1(uniqid(mt_rand(), true)) . '.' . $ext;

        copy($this->getUploadRootDir() . '/' . $this->fileUrl, $this->getUploadRootDir() . '/' . $newFileUrl);

        $this->fileUrl = $newFileUrl;
    }

    public function copy()
    {
        $clone = clone $this;
        $clone->copyFile();

        return $clone;
    }
}
