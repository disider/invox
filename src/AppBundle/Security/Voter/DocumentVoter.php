<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Security\Voter;

use AppBundle\Entity\Document;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class DocumentVoter extends Voter
{
    const DOCUMENT_COPY = 'DOCUMENT_COPY';
    const DOCUMENT_DELETE = 'DOCUMENT_DELETE';
    const DOCUMENT_EDIT = 'DOCUMENT_EDIT';
    const DOCUMENT_PRINT = 'DOCUMENT_PRINT';
    const DOCUMENT_VIEW = 'DOCUMENT_VIEW';

    protected function supports($attribute, $subject)
    {
        return $subject instanceof Document && in_array($attribute, [
            self::DOCUMENT_COPY,
            self::DOCUMENT_DELETE,
            self::DOCUMENT_EDIT,
            self::DOCUMENT_PRINT,
            self::DOCUMENT_VIEW,
            ]);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User)
            return false;

        /** @var User $user */
        switch($attribute) {
            case self::DOCUMENT_COPY:
            case self::DOCUMENT_DELETE:
            case self::DOCUMENT_EDIT:
                return $user->canManageDocument($subject);

            case self::DOCUMENT_PRINT:
            case self::DOCUMENT_VIEW:
                return $user->canManageDocument($subject) || $user->isAccountantFor($subject->getCompany());
        }


        return $user->canManageDocument($subject);
    }
}
