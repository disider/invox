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

use AppBundle\Entity\Service;
use AppBundle\Entity\User;
use AppBundle\Model\Module;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ServiceVoter extends BaseVoter
{
    const SERVICE_DELETE = 'SERVICE_DELETE';
    const SERVICE_EDIT = 'SERVICE_EDIT';

    protected function supports($attribute, $subject)
    {
        return $subject instanceof Service && in_array($attribute, [
                self::SERVICE_DELETE,
                self::SERVICE_EDIT,
            ]);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var User $user */
        $user = $token->getUser();

        if (!($user instanceof User))
            return false;

        if ($user->isSuperadmin() || ($user->ownsService($subject) && $this->isModuleEnabled($subject->getCompany(), Module::SERVICES_MODULE))) {
            return true;
        }

        return false;
    }
}
