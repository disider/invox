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

use AppBundle\Entity\DocumentTemplatePerCompany;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class DocumentTemplatePerCompanyVoter extends Voter
{
    const DOCUMENT_TEMPLATE_PER_COMPANY_EDIT = 'DOCUMENT_TEMPLATE_PER_COMPANY_EDIT';
    const DOCUMENT_TEMPLATE_PER_COMPANY_PREVIEW = 'DOCUMENT_TEMPLATE_PER_COMPANY_PREVIEW';
    const DOCUMENT_TEMPLATE_PER_COMPANY_RENDER = 'DOCUMENT_TEMPLATE_PER_COMPANY_RENDER';
    const DOCUMENT_TEMPLATE_PER_COMPANY_RESTORE = 'DOCUMENT_TEMPLATE_PER_COMPANY_RESTORE';

    protected function supports($attribute, $subject)
    {
        return $subject instanceof DocumentTemplatePerCompany && in_array($attribute, [
            self::DOCUMENT_TEMPLATE_PER_COMPANY_EDIT,
            self::DOCUMENT_TEMPLATE_PER_COMPANY_PREVIEW,
            self::DOCUMENT_TEMPLATE_PER_COMPANY_RENDER,
            self::DOCUMENT_TEMPLATE_PER_COMPANY_RESTORE,
            ]);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User)
            return false;

        /** @var User $user */
        switch ($attribute) {
            case self::DOCUMENT_TEMPLATE_PER_COMPANY_EDIT:
            case self::DOCUMENT_TEMPLATE_PER_COMPANY_PREVIEW:
            case self::DOCUMENT_TEMPLATE_PER_COMPANY_RENDER:
            case self::DOCUMENT_TEMPLATE_PER_COMPANY_RESTORE:
                return $user->isSuperadmin() || $user->ownsCompany($subject->getCompany());
        }

        return false;
    }
}
