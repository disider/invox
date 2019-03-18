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

use App\Entity\ParagraphTemplate;
use App\Entity\User;
use App\Model\Module;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ParagraphTemplateVoter extends BaseVoter
{
    const PARAGRAPH_TEMPLATE_DELETE = 'PARAGRAPH_TEMPLATE_DELETE';
    const PARAGRAPH_TEMPLATE_EDIT = 'PARAGRAPH_TEMPLATE_EDIT';

    protected function supports($attribute, $subject)
    {
        return $subject instanceof ParagraphTemplate && in_array(
                $attribute,
                [
                    self::PARAGRAPH_TEMPLATE_DELETE,
                    self::PARAGRAPH_TEMPLATE_EDIT,
                ]
            );
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var User $user */
        $user = $token->getUser();

        if (!($user instanceof User)) {
            return false;
        }

        switch ($attribute) {
            case self::PARAGRAPH_TEMPLATE_DELETE:
            case self::PARAGRAPH_TEMPLATE_EDIT:
                return $user->ownsParagraphTemplate($subject) && $this->isModuleEnabled(
                        $subject->getCompany(),
                        Module::WORKING_NOTES_MODULE
                    );
        }

        return false;
    }
}
