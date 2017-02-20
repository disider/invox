<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Validator\Constraints;

use AppBundle\Entity\Invite;
use AppBundle\Entity\PettyCashNote;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidInviteValidator extends ConstraintValidator
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param Invite $invoicePerNote
     * @param ValidInvite $constraint
     */
    public function validate($invoicePerNote, Constraint $constraint)
    {
        $user = $this->tokenStorage->getToken()->getUser();
        
        if ($invoicePerNote->getEmail() == $user->getEmail()) {
            $this->context->buildViolation('error.cannot_invite_self')
                ->atPath('email')
                ->addViolation();
        }
    }
}
