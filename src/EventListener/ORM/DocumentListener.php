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

use App\Entity\Document;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

class DocumentListener implements EventSubscriber
{
    private $orders = [];
    private $invoices = [];
    private $recurrences = [];

    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::preUpdate,
            Events::preRemove,
            Events::postFlush,
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $document = $args->getObject();

        if (!($document instanceof Document)) {
            return;
        }

        /* @var Document $document */
        $document->copyCompanyDetails();

        $this->recurrences[] = $document->getRecurrence();
        $this->updateDocument($document);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $document = $args->getObject();

        if (!($document instanceof Document)) {
            return;
        }

        if ($args->hasChangedField('recurrence')) {
            $this->recurrences[] = $args->getOldValue('recurrence');
            $this->recurrences[] = $args->getNewValue('recurrence');
        }

        $this->updateDocument($document);

        $em = $args->getEntityManager();

        $uow = $em->getUnitOfWork();
        $meta = $em->getClassMetadata(get_class($document));
        $uow->recomputeSingleEntityChangeSet($meta, $document);
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $document = $args->getObject();

        if (!($document instanceof Document)) {
            return;
        }

        $this->recurrences[] = $document->getRecurrence();
    }

    public function postFlush(PostFlushEventArgs $args)
    {
        $em = $args->getEntityManager();

        $flush = false;

        /** @var Document $order */
        foreach ($this->orders as $order) {
            $flush = true;
            $order->setStatus(Document::NO_STATUS);

            $em->persist($order);
        }

        $this->orders = [];

        /** @var Document $order */
        foreach ($this->invoices as $invoice) {
            $flush = true;
            $invoice->setStatus(Document::NO_STATUS);

            $em->persist($invoice);
        }

        $this->invoices = [];

        foreach ($this->recurrences as $recurrence) {
            if ($recurrence) {
                $flush = true;
                $em->refresh($recurrence);
                $recurrence->calculateNextDueDate();
                $em->persist($recurrence);
            }
        }

        $this->recurrences = [];

        if ($flush) {
            $em->flush();
        }
    }

    private function updateDocument(Document $document)
    {
        $document->calculateTotals();
        $document->updateStatus();
        $document->updateDirection();

        if ($document->hasLinkedOrder()) {
            $this->orders[] = $document->getLinkedOrder();
        }

        if ($document->hasLinkedInvoice()) {
            $this->invoices[] = $document->getLinkedInvoice();
        }
    }
}
