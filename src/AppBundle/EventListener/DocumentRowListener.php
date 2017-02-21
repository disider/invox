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

use AppBundle\Entity\Document;
use AppBundle\Entity\DocumentRow;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

class DocumentRowListener implements EventSubscriber
{
    private $documents = [];

    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::preUpdate,
            Events::postFlush,
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        if (!($object instanceof DocumentRow)) {
            return;
        }

        $object->calculateTotals();
        $this->documents[] = $object->getDocument();
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $object = $args->getObject();
        if (!($object instanceof DocumentRow)) {
            return;
        }

        $object->calculateTotals();
        $this->documents[] = $object->getDocument();

        $em = $args->getEntityManager();

        $uow = $em->getUnitOfWork();
        $meta = $em->getClassMetadata(get_class($object));
        $uow->recomputeSingleEntityChangeSet($meta, $object);
    }

    public function postFlush(PostFlushEventArgs $args)
    {
        $em = $args->getEntityManager();

        $flush = count($this->documents) > 0;

        /** @var Document $document */
        foreach($this->documents as $document) {
            $document->calculateTotals();

            $em->persist($document);
        }

        $this->documents = [];

        if($flush) {
            $em->flush();
        }
    }
}
