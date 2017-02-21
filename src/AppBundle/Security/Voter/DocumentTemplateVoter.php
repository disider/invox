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

use AppBundle\Entity\DocumentTemplate;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class DocumentTemplateVoter extends Voter
{
    const DOCUMENT_TEMPLATE_PREVIEW = 'DOCUMENT_TEMPLATE_PREVIEW';
    const DOCUMENT_TEMPLATE_RESTORE = 'DOCUMENT_TEMPLATE_RESTORE';

    protected function supports($attribute, $subject)
    {
        return $subject instanceof DocumentTemplate && in_array($attribute, [
            self::DOCUMENT_TEMPLATE_PREVIEW,
            self::DOCUMENT_TEMPLATE_RESTORE,
            ]);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User)
            return false;

        /** @var User $user */
        switch ($attribute) {
            case self::DOCUMENT_TEMPLATE_PREVIEW:
            case self::DOCUMENT_TEMPLATE_RESTORE:
                return $user->isSuperadmin();
        }

        return false;
    }
}
