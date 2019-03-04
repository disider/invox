<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\EventListener\ORM;

use AppBundle\Entity\Recurrence;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

class RecurrenceListener implements EventSubscriber
{

    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->updateRecurrence($args);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $this->updateRecurrence($args);
    }

    private function updateRecurrence(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        if (!($object instanceof Recurrence)) {
            return;
        }

        /* @var Recurrence $object */
        $object->calculateNextDueDate();
    }
}
