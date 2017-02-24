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

use AppBundle\Entity\Customer;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CustomerVoter extends Voter
{
    const CUSTOMER_DELETE = 'CUSTOMER_DELETE';
    const CUSTOMER_EDIT = 'CUSTOMER_EDIT';

    protected function supports($attribute, $subject)
    {
        return $subject instanceof Customer && in_array($attribute, [
            self::CUSTOMER_DELETE,
            self::CUSTOMER_EDIT
            ]);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var Customer $subject */
        /** @var User $user */
        $user = $token->getUser();

        if ($attribute == self::CUSTOMER_DELETE) {
            if ($user->canDeleteCustomer($subject)) {
                return true;
            }
        }

        if ($attribute == self::CUSTOMER_EDIT) {
            if ($user->canEditCustomer($subject) || $subject->getCompany()->hasSalesAgent($user)) {
                return true;
            }
        }

        return false;
    }
}
