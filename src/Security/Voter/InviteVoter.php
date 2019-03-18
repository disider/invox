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

use App\Entity\Invite;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class InviteVoter extends Voter
{
    const INVITE_ACCEPT = 'INVITE_ACCEPT';
    const INVITE_REFUSE = 'INVITE_REFUSE';
    const INVITE_DELETE = 'INVITE_DELETE';

    protected function supports($attribute, $subject)
    {
        return $subject instanceof Invite && in_array(
                $attribute,
                [
                    self::INVITE_ACCEPT,
                    self::INVITE_REFUSE,
                    self::INVITE_DELETE,
                ]
            );
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var Invite $subject */
        $user = $token->getUser();

        if (!($user instanceof User)) {
            return false;
        }

        if ($attribute == self::INVITE_DELETE) {
            return $user->ownsCompany($subject->getCompany());
        }

        /** @var User $user */
        return ($user->isSuperadmin() || ($user->getEmail() == $subject->getEmail()));
    }
}
