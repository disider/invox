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
use AppBundle\Model\DocumentType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidLinkedRecurrenceValidator extends ConstraintValidator
{
    /**
     * @param Document $document
     * @param ValidLinkedDocuments $constraint
     */
    public function validate($document, Constraint $constraint)
    {
        if($document->hasRecurrence()) {
            if ($document->getCompany() != $document->getRecurrence()->getCompany()) {
                $this->context->buildViolation('error.invalid_recurrence')
                    ->atPath('recurrence')
                    ->addViolation();
            }

            if ($document->getLinkedCustomer() != $document->getRecurrence()->getCustomer()) {
                $this->context->buildViolation('error.invalid_recurrence')
                    ->atPath('recurrence')
                    ->addViolation();
            }
        }

    }

}
