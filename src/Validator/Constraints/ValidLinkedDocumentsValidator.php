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

class ValidLinkedDocumentsValidator extends ConstraintValidator
{
    /**
     * @param Document $document
     * @param ValidLinkedDocuments $constraint
     */
    public function validate($document, Constraint $constraint)
    {
        if ($document->hasLinkedOrder()) {
            $this->validateLinkedDocument($document, $document->getLinkedOrder(), DocumentType::ORDER, 'linkedOrder', 'invalid_order');
        }

        if ($document->hasLinkedCreditNote()) {
            $this->validateLinkedDocument($document, $document->getLinkedCreditNote(), DocumentType::CREDIT_NOTE, 'linkedCreditNote', 'invalid_credit_note');
        }

        if ($document->hasLinkedInvoice()) {
            $this->validateLinkedDocument($document, $document->getLinkedInvoice(), DocumentType::INVOICE, 'linkedInvoice', 'invalid_invoice');
        }
    }

    private function validateLinkedDocument(Document $document, Document $linkedDocument, $type, $field, $error)
    {
        if (!$linkedDocument->is($type)) {
            $this->context->buildViolation('error.invalid_document_type')
                ->atPath($field)
                ->addViolation();
        }

        if (!$document->hasSameCompanyAs($linkedDocument)) {
            $this->context->buildViolation('error.' . $error)
                ->atPath($field)
                ->addViolation();
        }
    }
}
