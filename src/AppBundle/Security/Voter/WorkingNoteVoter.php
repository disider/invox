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
use AppBundle\Entity\WorkingNote;
use AppBundle\Model\Module;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class WorkingNoteVoter extends BaseVoter
{
    const WORKING_NOTE_DELETE = 'WORKING_NOTE_DELETE';
    const WORKING_NOTE_EDIT = 'WORKING_NOTE_EDIT';
    const WORKING_NOTE_PRINT = 'WORKING_NOTE_PRINT';
    const WORKING_NOTE_RENDER = 'WORKING_NOTE_RENDER';
    const WORKING_NOTE_VIEW = 'WORKING_NOTE_VIEW';

    protected function supports($attribute, $subject)
    {
        return $subject instanceof WorkingNote && in_array($attribute, [
                self::WORKING_NOTE_DELETE,
                self::WORKING_NOTE_EDIT,
                self::WORKING_NOTE_PRINT,
                self::WORKING_NOTE_RENDER,
                self::WORKING_NOTE_VIEW
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
            case self::WORKING_NOTE_DELETE:
            case self::WORKING_NOTE_PRINT:
            case self::WORKING_NOTE_EDIT:
            case self::WORKING_NOTE_RENDER:
            case self::WORKING_NOTE_VIEW:
                return $user->ownsWorkingNote($subject) && $this->isModuleEnabled($subject->getCompany(), Module::WORKING_NOTES_MODULE);
        }

        return false;
    }
}
