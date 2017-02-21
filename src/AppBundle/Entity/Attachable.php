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

use AppBundle\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

abstract class Attachable
{
    /** @var int */
    protected $id;

    /**
     * @Assert\NotBlank(message="error.empty_file_name")
     * @var string
     */
    protected $fileName;

    /**
     * @Assert\NotBlank(message="error.empty_file_url")
     * @var string
     */
    protected $fileUrl;

    /** @var AttachmentContainer */
    protected $container;

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

    public function getContainer()
    {
        return $this->container;
    }

    public function setContainer(AttachmentContainer $container = null)
    {
        if($container == null) {
            $this->removeFile();
        }
        
        $this->container = $container;
    }

    private function removeFile()
    {
        @unlink($this->getUploadRootDir() . '/' . $this->fileUrl);
    }

    public function getWebPath()
    {
        return null == $this->fileUrl
            ? ""
            : $this->getUploadDir() . '/' . $this->fileUrl;
    }

    public function getPath()
    {
        return null == $this->fileUrl
            ? ""
            : $this->getUploadRootDir() . '/' . $this->fileUrl;
    }

    public function getUploadDir()
    {
        return $this->getContainer()->getAttachmentsUploadDir();
    }

    public function getUploadRootDir()
    {
        return $this->getContainer()->getAttachmentsUploadRootDir();
    }

    public function getUploaderType()
    {
        return 'attachable';
    }
}
