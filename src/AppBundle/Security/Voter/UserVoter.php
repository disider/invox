<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Security\Voter;

use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
    const USER_DELETE = 'USER_DELETE';
    const USER_EDIT = 'USER_EDIT';
    const USER_IMPERSONATE = 'USER_IMPERSONATE';

    protected function supports($attribute, $subject)
    {
        return $subject instanceof User && in_array($attribute, [
            self::USER_IMPERSONATE,
            self::USER_DELETE,
            self::USER_EDIT
            ]);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var User $user */
        $user = $token->getUser();

        if ($attribute == self::USER_DELETE) {
            if ($user->isSuperadmin() || ($user->isManagingMultipleCompanies() && $user->hasCompanyManager($subject)) && !$user->isSameAs($subject)) {
                return true;
            }
        }
        if ($attribute == self::USER_EDIT) {
            if ($user->isSuperadmin() || ($user->isManagingMultipleCompanies() && $user->hasCompanyManager($subject))) {
                return true;
            }
        }
        if ($attribute == self::USER_IMPERSONATE) {
            if ($user->isSuperadmin() && !$user->isSameAs($subject)) {
                return true;
            }
        }

        return false;
    }
}
