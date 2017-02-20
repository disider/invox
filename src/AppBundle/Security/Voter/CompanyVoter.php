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

use AppBundle\Entity\Company;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CompanyVoter extends Voter
{
    const COMPANY_ACCOUNTANT = 'COMPANY_ACCOUNTANT';
    const COMPANY_DELETE = 'COMPANY_DELETE';
    const COMPANY_DESELECT = 'COMPANY_DESELECT';
    const COMPANY_DISCONNECT_ACCOUNTANT = 'COMPANY_DISCONNECT_ACCOUNTANT';
    const COMPANY_EDIT = 'COMPANY_EDIT';
    const COMPANY_SELECT = 'COMPANY_SELECT';
    const COMPANY_VIEW = 'COMPANY_VIEW';
    const LIST_DOCUMENT_TEMPLATES_PER_COMPANY = 'LIST_DOCUMENT_TEMPLATES_PER_COMPANY';

    protected function supports($attribute, $subject)
    {
        return $subject instanceof Company && in_array($attribute, [
                self::COMPANY_ACCOUNTANT,
                self::COMPANY_DELETE,
                self::COMPANY_DESELECT,
                self::COMPANY_DISCONNECT_ACCOUNTANT,
                self::COMPANY_EDIT,
                self::COMPANY_SELECT,
                self::COMPANY_VIEW,
                self::LIST_DOCUMENT_TEMPLATES_PER_COMPANY,
            ]);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!($user instanceof User)) {
            return false;
        }

        /** @var User $user */
        /** @var Company $subject */

        switch ($attribute) {
            case self::COMPANY_VIEW:
                return ($subject->hasManager($user) && !$user->ownsCompany($subject)) || $user->isAccountantFor($subject) || $user->isSalesAgentFor($subject);
            case self::COMPANY_DESELECT:
            case self::COMPANY_SELECT:
                return $user->canManageCompany($subject) || $user->isAccountantFor($subject) || $user->isSalesAgentFor($subject);
            case self::COMPANY_ACCOUNTANT:
                return (($user->isSuperadmin() || $user->ownsCompany($subject)));
            case self::COMPANY_DISCONNECT_ACCOUNTANT:
                return (($user->isSuperadmin() || $user->ownsCompany($subject)) && $subject->hasAccountant());
            case self::COMPANY_DELETE:
            case self::COMPANY_EDIT:
            case self::LIST_DOCUMENT_TEMPLATES_PER_COMPANY:
                return ($user->isSuperadmin() || $user->ownsCompany($subject));
        }

        return false;
    }
}
