<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Features\App;

use App\Entity\Account;
use App\Entity\Document;
use App\Entity\PettyCashNote;
use App\Entity\PettyCashNoteAttachment;
use Behat\Gherkin\Node\TableNode;

class PettyCashContext extends AbstractMinkContext
{
    /**
     * @Given /^there is a petty cash note:$/
     * @Given /^there are petty cash notes:$/
     */
    public function thereAreEntities(TableNode $table)
    {
        foreach ($table->getHash() as $key => $values) {
            $entity = $this->buildEntities(
                $this->getValue($values, 'company'),
                $this->getValue($values, 'ref', 123),
                $this->getValue($values, 'accountFrom'),
                $this->getValue($values, 'accountTo'),
                $values['amount'],
                $this->getDateValue($values, 'recordedAt', 'now')
            );

            $this->getPettyCashNoteRepository()->save($entity);
        }
    }

    /**
     * @Given /^there is a petty cash note attachment:$/
     */
    public function thereIsAPettyCashNoteAttachment(TableNode $table)
    {
        foreach ($table->getHash() as $key => $values) {
            /** @var PettyCashNote $pettyCashNote */
            $pettyCashNote = $this->getPettyCashNoteRepository()->findOneByRef($values['pettyCashNote']);

            $this->buildAttachment(
                $pettyCashNote,
                $values['fileName'],
                $values['fileUrl'],
                PettyCashNoteAttachment::class
            );

            $this->getDocumentRepository()->save($pettyCashNote);
        }
    }

    private function buildEntities($companyName, $ref, $accountFromName, $accountToName, $amount, \Datetime $recordedAt)
    {
        /** @var Account $accountFrom */
        $accountFrom = $this->getAccountRepository()->findOneByName($accountFromName);

        /** @var Account $accountTo */
        $accountTo = $this->getAccountRepository()->findOneByName($accountToName);

        if (!$accountFrom && !$accountTo) {
            throw new \LogicException('Account from or account to must be set');
        }

        $company = null;
        if (!$companyName) {
            $company = $accountFrom ? $accountFrom->getCompany() : $accountTo->getCompany();
        } else {
            $company = $this->getCompanyRepository()->findOneByName($companyName);
        }

        $pettyCashNote = PettyCashNote::create($company, $ref, $amount, $recordedAt);
        $pettyCashNote->setAccountFrom($accountFrom);
        $pettyCashNote->setAccountTo($accountTo);

        return $pettyCashNote;
    }

    /**
     * @Given /^there is an invoice linked to a petty cash note:$/
     * @Given /^there are invoices linked to petty cash notes:$/
     */
    public function thereAreInvoicesLinkedToPettyCashNotes(TableNode $table)
    {
        foreach ($table->getHash() as $key => $values) {
            $entity = $this->addInvoicePerNote(
                $values['invoice'],
                $values['note'],
                $values['amount']
            );

            $this->getPettyCashNoteRepository()->save($entity);
        }
    }

    private function addInvoicePerNote($invoiceRef, $noteRef, $amount)
    {
        /** @var Document $invoice */
        $invoice = $this->getDocumentRepository()->findOneByRef($invoiceRef);

        /** @var PettyCashNote $note */
        $note = $this->getPettyCashNoteRepository()->findOneByRef($noteRef);

        $invoicePerNote = $note->linkInvoice($invoice, $amount);
        $invoice->addPettyCashNote($invoicePerNote);

        return $note;
    }
}
