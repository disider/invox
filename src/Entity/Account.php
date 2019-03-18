<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

class Account
{
    /** @var int */
    private $id;

    /**
     * @var Company
     */
    private $company;

    /**
     * @Assert\NotBlank(message="error.empty_name")
     *
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /** @var string */
    private $iban;

    /**
     * @Assert\NotBlank(message="error.empty_amount")
     * @var float
     */
    private $initialAmount = 0;

    /**
     * @Assert\NotBlank(message="error.empty_amount")
     * @var float
     */
    private $currentAmount = 0;

    /**
     * @var ArrayCollection
     */
    private $incomingPettyCashNotes;

    /**
     * @var ArrayCollection
     */
    private $outgoingPettyCashNotes;

    public static function create(Company $company, $type, $name, $initialAmount, $currentAmount, $iban)
    {
        $entity = new self();
        $entity->setType($type);
        $entity->setName($name);
        $entity->setIban($iban);
        $entity->setInitialAmount($initialAmount);
        $entity->setCurrentAmount($currentAmount);

        $company->addAccount($entity);

        return $entity;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function __construct()
    {
        $this->incomingPettyCashNotes = new ArrayCollection();
        $this->outgoingPettyCashNotes = new ArrayCollection();
    }

    public function setId($id)
    {
        $this->id = $id;
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

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        if (!in_array($type, AccountType::getTypes())) {
            throw new \LogicException('Invalid account type: '.$type);
        }

        $this->type = $type;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getInitialAmount()
    {
        return $this->initialAmount;
    }

    public function setInitialAmount($initialAmount)
    {
        $this->initialAmount = $initialAmount;
    }

    public function getCurrentAmount()
    {
        return $this->currentAmount;
    }

    public function setCurrentAmount($currentAmount)
    {
        $this->currentAmount = $currentAmount;
    }

    public function getIban()
    {
        return $this->iban;
    }

    public function setIban($iban)
    {
        $this->iban = $iban;
    }

    public function updateCurrentAmount($amount)
    {
        $this->currentAmount += $amount;
    }

    public function getBalance()
    {
        return $this->currentAmount - $this->initialAmount;
    }

    public function calculateCurrentAmount()
    {
        $amount = 0;

        /** @var PettyCashNote $note */
        foreach ($this->incomingPettyCashNotes as $note) {
            $amount += $note->getAmount();
        }

        /** @var PettyCashNote $note */
        foreach ($this->outgoingPettyCashNotes as $note) {
            $amount -= $note->getAmount();
        }

        $this->currentAmount = $amount;
    }
}
