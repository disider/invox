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

use AppBundle\Entity\PettyCashNote;
use AppBundle\Model\LocaleFormatter;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidPettyCashNoteValidator extends ConstraintValidator
{
    /**
     * @var LocaleFormatter
     */
    private $localeFormatter;

    public function __construct(LocaleFormatter $localeFormatter) {

        $this->localeFormatter = $localeFormatter;
    }
    
    /**
     * @param PettyCashNote $pettyCashNote
     * @param ValidPettyCashNote $constraint
     */
    public function validate($pettyCashNote, Constraint $constraint)
    {
        if(!($pettyCashNote->getAccountTo() || $pettyCashNote->getAccountFrom())) {
            $this->context->buildViolation('error.no_account_selected')
                ->addViolation();
        }
        
        if ($pettyCashNote->getAmount() < $pettyCashNote->getInvoicesTotal()) {
            $this->context->buildViolation('error.invoices_exceeding_note_amount')
                ->setParameter('%invoicesAmount%', $this->localeFormatter->format($pettyCashNote->getInvoicesTotal()))
                ->setParameter('%noteAmount%', $this->localeFormatter->format($pettyCashNote->getAmount()))
                ->atPath('invoices')
                ->addViolation();
        }

        if ($pettyCashNote->getAccountFrom() && ($pettyCashNote->getAccountFrom() == $pettyCashNote->getAccountTo())) {
            $this->context->buildViolation('error.cannot_transfer_to_same_account')
                ->addViolation();
        }

        if ($pettyCashNote->getAccountFrom() && $pettyCashNote->getAccountTo() && $pettyCashNote->hasInvoices()) {
            $this->context->buildViolation('error.cannot_add_invoices_if_transfer')
                ->addViolation();
        }

        if ($pettyCashNote->isIncome() && $pettyCashNote->hasIncomingInvoices()) {
            $this->context->buildViolation('error.cannot_add_incoming_invoice_if_income')
                ->addViolation();
        }

        if ($pettyCashNote->isOutcome() && $pettyCashNote->hasOutgoingInvoices()) {
            $this->context->buildViolation('error.cannot_add_outgoing_invoice_if_outcome')
                ->addViolation();
        }
    }
}
