<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\Account;
use AppBundle\Entity\InvoicePerNote;
use AppBundle\Entity\PettyCashNote;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

class PettyCashNoteListener implements EventSubscriber
{
    private $accounts = [];

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

        if (!$object instanceof PettyCashNote) {
            return;
        }

        $object->updateType();
        
        $this->updateAccount(-$object->getAmount(), $object->getAccountFrom());
        $this->updateAccount($object->getAmount(), $object->getAccountTo());
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $object = $args->getObject();

        if (!$object instanceof PettyCashNote) {
            return;
        }

        /** @var PettyCashNote $object */

        if ($args->hasChangedField('amount')) {
            $oldAmount = $args->getOldValue('amount');
            $newAmount = $args->getNewValue('amount');

            $this->updateAccount($newAmount, $object->getAccountFrom(), $oldAmount);
            $this->updateAccount($newAmount, $object->getAccountTo(), $oldAmount);
        }

        $object->updateType();
        $this->updateAmount($args, 'accountFrom', -$object->getAmount());
        $this->updateAmount($args, 'accountTo', $object->getAmount());

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

        if (!$object instanceof PettyCashNote) {
            return;
        }

        $this->updateAccount($object->getAmount(), $object->getAccountFrom());
        $this->updateAccount(-$object->getAmount(), $object->getAccountTo());
    }

    public function postFlush(PostFlushEventArgs $args)
    {
        $em = $args->getEntityManager();

        $flush = count($this->accounts) > 0;

        foreach ($this->accounts as $account) {
            $em->persist($account);
        }

        $this->accounts = [];

        if ($flush) {
            $em->flush();
        }
    }

    private function updateAccount($newAmount, Account $account = null, $oldAmount = 0)
    {
        if ($account) {
            $account->updateCurrentAmount($newAmount - $oldAmount);
            $this->accounts[$account->getId()] = $account;
        }
    }

    private function updateAmount(PreUpdateEventArgs $args, $field, $amount)
    {
        if ($args->hasChangedField($field)) {
            $oldAccount = $args->getOldValue($field);
            $newAccount = $args->getNewValue($field);

            $this->updateAccount(-$amount, $oldAccount);
            $this->updateAccount($amount, $newAccount);
        }
    }
}
