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

use App\Entity\Product;
use App\Entity\WarehouseRecord;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

class ProductListener implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::preUpdate,
            Events::preRemove,
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        if ($object instanceof WarehouseRecord) {
            /** @var WarehouseRecord $object */

            $object->getProduct()->updateStock($object->getStockBalance());
        } else {
            if ($object instanceof Product) {
                $object->setCurrentStock($object->getInitialStock());
            }
        }
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $object = $args->getObject();

        if (!$object instanceof Product) {
            return;
        }

        if ($args->hasChangedField('initialStock')) {
            $oldStock = $args->getOldValue('initialStock');
            $newStock = $args->getNewValue('initialStock');

            $object->setCurrentStock($object->getCurrentStock() + $newStock - $oldStock);
        }

        // We are doing a update, so we must force Doctrine to update the
        // changeset in case we changed something above
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();
        $meta = $em->getClassMetadata(get_class($object));
        $uow->recomputeSingleEntityChangeSet($meta, $object);
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        if ($object instanceof WarehouseRecord) {
            /** @var WarehouseRecord $object */

            $object->getProduct()->updateStock(-$object->getStockBalance());
        }
    }
}
