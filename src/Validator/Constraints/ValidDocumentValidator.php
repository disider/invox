<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Validator\Constraints;

use App\Entity\Document;
use App\Model\DocumentType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidDocumentValidator extends ConstraintValidator
{
    /**
     * @param Document $document
     * @param ValidDocument $constraint
     */
    public function validate($document, Constraint $constraint)
    {
        if (($document->is(DocumentType::RECEIPT) || $document->is(DocumentType::INVOICE) || $document->is(
                    DocumentType::CREDIT_NOTE
                )) && !$document->hasDirection()) {
            $this->context->buildViolation('error.empty_direction')
                ->atPath('direction')
                ->addViolation();
        }

        if ($document->is(DocumentType::INVOICE) && !$document->hasValidUntil()) {
            $this->context->buildViolation('error.empty_valid_until')
                ->atPath('validUntil')
                ->addViolation();
        }

        if (($document->is(DocumentType::RECEIPT) || $document->is(
                    DocumentType::INVOICE
                )) && !$document->hasPaymentType()) {
            $this->context->buildViolation('error.empty_payment_type')
                ->atPath('paymentType')
                ->addViolation();
        }
    }
}
