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

use AppBundle\Entity\Medium;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Oneup\UploaderBundle\Uploader\Orphanage\OrphanageManager;

class MediumListener implements EventSubscriber
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
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $medium = $args->getObject();

        if (!($medium instanceof Medium)) {
            return;
        }

        $storage = $this->orphanageManager->get('medium');

        $storage->uploadFiles();
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $medium = $args->getObject();

        if (!($medium instanceof Medium)) {
            return;
        }

        $em = $args->getEntityManager();

        $uow = $em->getUnitOfWork();
        $meta = $em->getClassMetadata(get_class($medium));
        $uow->recomputeSingleEntityChangeSet($meta, $medium);
    }
}
