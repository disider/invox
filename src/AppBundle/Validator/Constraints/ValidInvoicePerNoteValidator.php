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

use AppBundle\Entity\InvoicePerNote;
use AppBundle\Entity\PettyCashNote;
use AppBundle\Model\LocaleFormatter;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidInvoicePerNoteValidator extends ConstraintValidator
{
    /**
     * @var LocaleFormatter
     */
    private $localeFormatter;

    public function __construct(LocaleFormatter $localeFormatter) {

        $this->localeFormatter = $localeFormatter;
    }
    
    /**
     * @param InvoicePerNote      $invoicePerNote
     * @param ValidInvoicePerNote $constraint
     */
    public function validate($invoicePerNote, Constraint $constraint)
    {
        $invoice = $invoicePerNote->getInvoice();

        if(!$invoice) {
            $this->context->buildViolation('error.invalid_invoice')
                ->atPath('invoice')
                ->addViolation();

            return;
        }

        $unpaidAmount = $invoice->getUnpaidAmount();

        /** @var PettyCashNote $note */
        foreach ($invoice->getPettyCashNotes() as $note) {
            if ($note->getId() == $invoicePerNote->getId()) {
                $unpaidAmount += $note->getAmount();
                break;
            }
        }

        if (abs($invoicePerNote->getAmount()) > abs($unpaidAmount)) {
            $this->context->buildViolation('error.amount_exceeding_invoice_amount')
                ->setParameter('%amount%', $this->localeFormatter->format(abs($invoicePerNote->getAmount())))
                ->setParameter('%invoiceAmount%', $this->localeFormatter->format(abs($unpaidAmount)))
                ->atPath('amount')
                ->addViolation();
        }
    }
}
