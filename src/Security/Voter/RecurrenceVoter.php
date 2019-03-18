<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Security\Voter;

use App\Entity\Recurrence;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class RecurrenceVoter extends Voter
{
    const RECURRENCE_DELETE = 'RECURRENCE_DELETE';
    const RECURRENCE_EDIT = 'RECURRENCE_EDIT';

    protected function supports($attribute, $subject)
    {
        return $subject instanceof Recurrence && in_array(
                $attribute,
                [
                    self::RECURRENCE_DELETE,
                    self::RECURRENCE_EDIT,
                ]
            );
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        return $user->canManageRecurrence($subject);
    }

}
