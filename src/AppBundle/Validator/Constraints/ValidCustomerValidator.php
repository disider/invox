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
use AppBundle\Repository\CustomerRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidCustomerValidator extends ConstraintValidator
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
     * @param Document $document
     * @param ValidCustomer $constraint
     */
    public function validate($document, Constraint $constraint)
    {
        $vatNumber = $document->getCustomerVatNumber();
        $customerName = $document->getCustomerName();

        if (!($document->hasLinkedCustomer())) {
            if (!trim($customerName)) {
                $this->context->buildViolation($constraint->emptyNameMessage)
                    ->atPath('customerName')
                    ->addViolation();
            }

            if (!trim($vatNumber)) {
                $this->context->buildViolation($constraint->emptyVatNumberMessage)
                    ->atPath('customerVatNumber')
                    ->addViolation();
            } else if (!preg_match('/[0-9]{11}/', $vatNumber)) {
                $this->context->buildViolation($constraint->invalidVatNumberMessage)
                    ->atPath('customerVatNumber')
                    ->addViolation();
            }
        }
    }
}
