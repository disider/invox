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

use App\Entity\Invite;
use App\Model\UserInterface;
use App\Security\UserPasswordEncoder;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

class UserListener implements EventSubscriber
{
    private $encoder;

    public function __construct(UserPasswordEncoder $encoder)
    {
        $this->encoder = $encoder;
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
        $object = $args->getObject();
        if ($object instanceof UserInterface) {
            $repo = $args->getObjectManager()->getRepository(Invite::class);
            $invites = $repo->findBy(['email' => $object->getEmail()]);
            /** @var Invite $invite */
            foreach ($invites as $invite) {
                $invite->setReceiver($object);
            }

            $this->updateUserFields($object);
        }
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $object = $args->getEntity();
        if ($object instanceof UserInterface) {
            $this->updateUserFields($object);

            // We are doing a update, so we must force Doctrine to update the
            // changeset in case we changed something above
            $em = $args->getEntityManager();
            $uow = $em->getUnitOfWork();
            $meta = $em->getClassMetadata(get_class($object));
            $uow->recomputeSingleEntityChangeSet($meta, $object);
        }
    }

    /**
     * This must be called on prePersist and preUpdate if the event is about a
     * user.
     *
     * @param UserInterface $user
     */
    protected function updateUserFields(UserInterface $user)
    {
        $this->encoder->encodePassword($user);
    }
}
