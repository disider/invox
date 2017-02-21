<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\EventListener;

use Oneup\UploaderBundle\Event\PostUploadEvent;
use Oneup\UploaderBundle\Event\PreUploadEvent;
use Oneup\UploaderBundle\Uploader\File\FilesystemFile;
use Oneup\UploaderBundle\Uploader\Orphanage\OrphanageManager;
use Oneup\UploaderBundle\Uploader\Storage\OrphanageStorageInterface;
use Symfony\Component\HttpFoundation\File\File;

class MediumUploadListener
{
    /**
     * @var OrphanageManager
     */
    private $orphanageManager;

    public function __construct(OrphanageManager $orphanageManager)
    {
        $this->orphanageManager = $orphanageManager;
    }

    public function onPreUpload(PreUploadEvent $event)
    {
        /** @var OrphanageStorageInterface $storage */
        $storage = $this->orphanageManager->get($event->getType());
        $files = $storage->getFiles();
        foreach ($files as $file) {
            @unlink($file);
        }

        $response = $event->getResponse();

        /** @var FilesystemFile $file */
        $file = $event->getFile();

        $response['fileName'] = $file->getClientOriginalName();
    }

    public function onPostUpload(PostUploadEvent $event)
    {
        /** @var File $file */
        $file = $event->getFile();
        $response = $event->getResponse();

        $response['fileUrl'] = $file->getFilename();
    }
}