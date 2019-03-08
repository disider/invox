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

use App\Entity\Medium;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MediumVoter extends Voter
{
    const MEDIUM_DELETE = 'MEDIUM_DELETE';
    const MEDIUM_EDIT = 'MEDIUM_EDIT';

    protected function supports($attribute, $subject)
    {
        return $subject instanceof Medium && in_array($attribute, [
                self::MEDIUM_DELETE,
                self::MEDIUM_EDIT
            ]);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var User $user */
        $user = $token->getUser();

        if ($attribute == self::MEDIUM_DELETE) {
            if ($user->canDeleteMedium($subject)) {
                return true;
            }
        }

        if ($attribute == self::MEDIUM_EDIT) {
            if ($user->canEditMedium($subject)) {
                return true;
            }
        }

        return false;
    }
}
