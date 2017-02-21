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

use AppBundle\Entity\PettyCashNote;
use AppBundle\Entity\User;
use AppBundle\Model\Module;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class PettyCashNoteVoter extends BaseVoter
{
    const PETTY_CASH_NOTE_DELETE = 'PETTY_CASH_NOTE_DELETE';
    const PETTY_CASH_NOTE_EDIT = 'PETTY_CASH_NOTE_EDIT';
    const PETTY_CASH_NOTE_VIEW = 'PETTY_CASH_NOTE_VIEW';

    protected function supports($attribute, $subject)
    {
        return $subject instanceof PettyCashNote && in_array($attribute, [
            self::PETTY_CASH_NOTE_DELETE,
            self::PETTY_CASH_NOTE_EDIT,
            self::PETTY_CASH_NOTE_VIEW,
            ]);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var User $user */
        $user = $token->getUser();

        if (!($user instanceof User)) {
            return false;
        }

        switch ($attribute) {
            case self::PETTY_CASH_NOTE_DELETE:
            case self::PETTY_CASH_NOTE_EDIT:
                return $user->canManageCompany($subject->getCompany())
                && $this->isModuleEnabled($subject->getCompany(), Module::PETTY_CASH_NOTES_MODULE);
            case self::PETTY_CASH_NOTE_VIEW:
                return
                    $user->isAccountant()
                    && $user->isAccountantFor($subject->getCompany())
                    && $this->isModuleEnabled($subject->getCompany(), Module::PETTY_CASH_NOTES_MODULE);

        }

        return false;
    }
}
