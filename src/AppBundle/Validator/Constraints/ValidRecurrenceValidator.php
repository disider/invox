<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Validator\Constraints;

use AppBundle\Entity\Document;
use AppBundle\Entity\Recurrence;
use AppBundle\Entity\Repository\CustomerRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidRecurrenceValidator extends ConstraintValidator
{
    /**
     * @var CustomerRepository
     */
    private $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param Recurrence $recurrence
     * @param ValidRecurrence $constraint
     */
    public function validate($recurrence, Constraint $constraint)
    {
        if (!$recurrence->hasCustomer()) {
            $this->context->buildViolation($constraint->selectCustomerMessage)
                ->atPath('customerName')
                ->addViolation();

        }
    }
}
