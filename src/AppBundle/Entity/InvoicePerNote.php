<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Entity;

use AppBundle\Validator\Constraints as AppAssert;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @JMS\ExclusionPolicy("all")
 * @AppAssert\ValidInvoicePerNote
 */
class InvoicePerNote
{
    /** @var int */
    private $id;

    /**
     * @var Document
     */
    private $invoice;

    /**
     * @var PettyCashNote
     */
    private $note;

    /**
     * @JMS\Expose()
     * @Assert\NotBlank(message="error.empty_amount")
     * @Assert\GreaterThan(0, message="error.non_positive_amount")
     *
     * @var float
     */
    private $amount = 0;

    public static function create(Document $invoice, PettyCashNote $note, $amount)
    {
        $entity = new self();
        $entity->invoice = $invoice;
        $entity->note = $note;
        $entity->amount = $amount;

        return $entity;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getInvoice()
    {
        return $this->invoice;
    }

    public function setInvoice(Document $invoice)
    {
        $this->invoice = $invoice;
        $invoice->addPettyCashNote($this);
    }

    public function getNote()
    {
        return $this->note;
    }

    public function setNote(PettyCashNote $note = null)
    {
        $this->note = $note;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getInvoiceTitle()
    {
        return $this->invoice ? $this->invoice->getFullRef() : '';
    }

    public function setInvoiceTitle($title)
    {

    }

    public function isOutgoing()
    {
        return $this->getInvoice()->isOutgoing();
    }

    public function isIncoming()
    {
        return $this->getInvoice()->isIncoming();
    }

    /**
     * @JMS\VirtualProperty()
     */
    public function getNoteId() {
        return $this->getNote()->getId();
    }

}
