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
use AppBundle\Entity\InvoicePerNote;
use AppBundle\Entity\PettyCashNote;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

class InvoicePerNoteListener implements EventSubscriber
{
    private $invoices = [];

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
        $object = $args->getObject();

        if (!$object instanceof InvoicePerNote) {
            return;
        }

        $this->invoices[$object->getInvoice()->getId()] = $object->getInvoice();
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $object = $args->getObject();

        if (!$object instanceof InvoicePerNote) {
            return;
        }

        $this->invoices[$object->getInvoice()->getId()] = $object->getInvoice();
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        if (!$object instanceof InvoicePerNote) {
            return;
        }

        $this->invoices[$object->getInvoice()->getId()] = $object->getInvoice();
    }

    public function postFlush(PostFlushEventArgs $args)
    {
        $em = $args->getEntityManager();

        $flush = count($this->invoices) > 0;

        /** @var Document $invoice */
        foreach ($this->invoices as $invoice) {
            if($invoice->getId()) {
                $invoice->calculateTotals();

                $invoice->updateStatus();

                $em->persist($invoice);
            }
        }

        $this->invoices = [];

        if ($flush) {
            $em->flush();
        }
    }
}
