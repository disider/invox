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

use App\Entity\Account;
use App\Entity\User;
use App\Model\Module;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class AccountVoter extends BaseVoter
{
    const ACCOUNT_DELETE = 'ACCOUNT_DELETE';
    const ACCOUNT_EDIT = 'ACCOUNT_EDIT';

    protected function supports($attribute, $subject)
    {
        return $subject instanceof Account && in_array($attribute, [
                self::ACCOUNT_DELETE,
                self::ACCOUNT_EDIT
            ]);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var User $user */
        $user = $token->getUser();

        if (!($user instanceof User))
            return false;

        if ($user->isSuperadmin()
            || ($user->ownsAccount($subject) && $this->isModuleEnabled($subject->getCompany(), Module::ACCOUNTS_MODULE))) {
            return true;
        }

        return false;
    }
}
