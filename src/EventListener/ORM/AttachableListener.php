<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\EventListener\ORM;

use App\Entity\Attachable;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Oneup\UploaderBundle\Uploader\Orphanage\OrphanageManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\SplFileInfo;

class AttachableListener implements EventSubscriber
{
    /**
     * @var OrphanageManager
     */
    private $orphanageManager;

    public function __construct(OrphanageManager $orphanageManager)
    {
        $this->orphanageManager = $orphanageManager;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::postPersist,
            Events::postUpdate,
        ];
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        if (!($object instanceof Attachable)) {
            return;
        }

        $this->moveFiles($object);
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        if (!($object instanceof Attachable)) {
            return;
        }

        $this->moveFiles($object);
    }

    /**
     * @param $attachable
     * @param $storage
     */
    protected function moveFiles(Attachable $attachable)
    {
        $storage = $this->orphanageManager->get($attachable->getUploaderType());

        $fs = new Filesystem();

        /** @var SplFileInfo $file */
        foreach ($storage->getFiles() as $file) {
            $source = $file->getPathname();

            $fs->mkdir($attachable->getUploadRootDir());
            $destination = $attachable->getUploadRootDir().'/'.$file->getFilename();

            if (is_file($source)) {
                $fs->rename($source, $destination);
            }
        }
    }
}
