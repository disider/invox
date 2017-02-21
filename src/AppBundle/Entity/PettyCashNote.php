<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Entity;

use AppBundle\Validator\Constraints as AppAssert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @AppAssert\ValidPettyCashNote
 */
class PettyCashNote extends AttachmentContainer
{
    const TRANSFER = 'transfer';
    const INCOME = 'income';
    const OUTCOME = 'outcome';

    /** @var int */
    private $id;

    /**
     * @var Company
     */
    private $company;

    /**
     * @Assert\NotBlank(message="error.empty_ref")
     * @var string
     */
    private $ref;

    /**
     * @Assert\NotBlank(message="error.empty_date")
     * @var \DateTime
     */
    private $recordedAt;

    /**
     * @Assert\GreaterThan(0, message="error.non_positive_amount")
     * @var float
     */
    private $amount = 0;

    /** @var string */
    private $type;

    /**
     * @var Account
     */
    private $accountFrom;

    /**
     * @var Account
     */
    private $accountTo;

    /**
     * @var string
     */
    private $description;

    /**
     * @Assert\Valid
     *
     * @var ArrayCollection
     */
    private $invoices;

    public static function create(Company $company, $ref, $amount, \DateTime $recordedAt)
    {
        $entity = new self();
        $entity->company = $company;
        $entity->ref = $ref;
        $entity->amount = $amount;
        $entity->recordedAt = $recordedAt;

        return $entity;
    }

    public static function createEmpty($ref, Company $company = null)
    {
        $entity = new self();
        $entity->company = $company;
        $entity->ref = $ref;

        return $entity;
    }

    public function __toString()
    {
        return $this->ref;
    }

    public function __construct()
    {
        parent::__construct();

        $this->recordedAt = new \DateTime();
        $this->invoices = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setCompany(Company $company)
    {
        $this->company = $company;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function getRef()
    {
        return $this->ref;
    }

    public function setRef($ref)
    {
        $this->ref = $ref;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getRecordedAt()
    {
        return $this->recordedAt;
    }

    public function setRecordedAt(\DateTime $recordedAt)
    {
        $this->recordedAt = $recordedAt;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getAccountFrom()
    {
        return $this->accountFrom;
    }

    public function setAccountFrom(Account $accountFrom = null)
    {
        $this->accountFrom = $accountFrom;
    }

    public function getAccountTo()
    {
        return $this->accountTo;
    }

    public function setAccountTo(Account $accountTo = null)
    {
        $this->accountTo = $accountTo;
    }

    public function getInvoices()
    {
        return $this->invoices;
    }

    public function setInvoices($invoices)
    {
        $this->invoices = $invoices;
    }

    public function addInvoice(InvoicePerNote $invoicePerNote)
    {
        $this->invoices->add($invoicePerNote);
        $invoicePerNote->setNote($this);
    }

    public function removeInvoice(InvoicePerNote $invoicePerNote)
    {
        $this->invoices->removeElement($invoicePerNote);
        $invoicePerNote->setNote(null);
    }

    public function linkInvoice(Document $invoice, $amount)
    {
        $invoicePerNote = InvoicePerNote::create($invoice, $this, $amount);

        $this->addInvoice($invoicePerNote);

        return $invoicePerNote;
    }

    public function canAddInvoice()
    {
        if ($this->getId() === null) {
            return true;
        }

        $total = $this->getInvoicesTotal();

        return $this->getAmount() > $total;
    }

    public function hasInvoices()
    {
        return count($this->invoices) > 0;
    }


    public function getInvoicesTotal()
    {
        $total = 0;

        /** @var InvoicePerNote $invoice */
        foreach ($this->invoices as $invoice) {
            $total += $invoice->getAmount();
        }
        return $total;
    }

    public function getUploadDir()
    {
        return $this->company->getUploadDir();
    }

    public function getAttachmentsUploadDir()
    {
        return $this->getUploadDir() . sprintf('/pettycashnotes/%s/attachments', $this->getId());
    }

    public function updateType()
    {
        if (!$this->accountFrom && !$this->accountTo) {
            throw new \LogicException('Account from or account to must be set');
        }

        $this->type = ($this->accountFrom && $this->accountTo)
            ? PettyCashNote::TRANSFER
            : ($this->accountTo ? PettyCashNote::INCOME : PettyCashNote::OUTCOME);
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function isSameAs(PettyCashNote $note)
    {
        return $note->getId() == $this->getId();
    }

    public function getCustomers()
    {
        $customers = new ArrayCollection();

        /** @var InvoicePerNote $invoice */
        foreach ($this->getInvoices() as $invoice) {

            if (!$customers->contains($invoice->getInvoice()->getLinkedCustomer()))
                $customers->add($invoice);
        }

        return $customers;
    }

    protected function buildAttachment()
    {
        return new PettyCashNoteAttachment();
    }

    public function isTransfer()
    {
        return $this->type == self::TRANSFER;
    }

    public function isIncome()
    {
        return $this->type == self::INCOME;
    }

    public function isOutcome()
    {
        return $this->type == self::OUTCOME;
    }

    public function getNotInvoicedTotal()
    {
        return $this->getAmount() - $this->getInvoicesTotal();
    }

    public function hasIncomingInvoices()
    {
        /** @var InvoicePerNote $invoice */
        foreach ($this->invoices as $invoice) {
            if ($invoice->isIncoming()) {
                return true;
            }
        }

        return false;
    }

    public function hasOutgoingInvoices()
    {
        /** @var InvoicePerNote $invoice */
        foreach ($this->invoices as $invoice) {
            if ($invoice->isOutgoing()) {
                return true;
            }
        }

        return false;
    }
}
