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

use AppBundle\Helper\WeekdayHelper;
use AppBundle\Validator\Constraints as AppAssert;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @AppAssert\ValidRecurrence
 * @JMS\ExclusionPolicy("all")
 */
class Recurrence
{
    const INCOMING = 'incoming';
    const OUTGOING = 'outgoing';

    const EVERYDAY = 'everyday';
    const EVERY_WEEK = 'every_week';
    const EVERY_MONTH = 'every_month';
    const EVERY_YEAR = 'every_year';

    const DAY_OF_THE_MONTH = 'day_of_the_month';
    const DAY_OF_THE_WEEK = 'day_of_the_week';

    /**
     * @var int
     * @JMS\Expose
     */
    private $id;

    /**
     * @Assert\NotBlank(message="error.empty_content")
     * @var string
     * @JMS\Expose
     */
    private $content;

    /**
     * @Assert\NotBlank(message="error.empty_direction")
     * @var string
     */
    private $direction;

    /**
     * @Assert\NotBlank(message="error.empty_amount")
     * @var string
     */
    private $amount;

    /**
     * @Assert\NotBlank(message="error.empty_start_at")
     * @var \DateTime
     */
    private $startAt;


    /**
     * @var \DateTime
     */
    private $nextDueDate;

    /** @var \DateTime */
    private $endAt;

    /**
     * @Assert\NotBlank(message="error.empty_repeats")
     * @var  string
     */
    private $repeats;

    /**
     * @Assert\NotBlank(message="error.empty_repeat_every")
     * @var int
     */
    private $repeatEvery;

    /** @var  string */
    private $repeatOn;

    /** @var int */
    private $occurrences;

    /** @var ArrayCollection|Document[] */
    private $linkedDocuments;

    /**
     * @var Customer
     */
    private $customer;

    /**
     * @Assert\NotBlank(message="error.empty_company")
     * @var Company
     */
    private $company;

    function __construct()
    {
        $this->linkedDocuments = new ArrayCollection();
        $this->repeats = self::EVERYDAY;
        $this->repeatEvery = 1;
    }

    public static function create($content, $direction, $startAt, $repeats, $repeatEvery, $customer, $company)
    {
        $entity = new self();
        $entity->setContent($content);
        $entity->setDirection($direction);
        $entity->setStartAt($startAt);
        $entity->setRepeats($repeats);
        $entity->setRepeatEvery($repeatEvery);
        $entity->setCompany($company);
        $entity->setCustomer($customer);

        return $entity;
    }

    function __toString()
    {
        return $this->getContent();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * @param string $direction
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;
    }

    /**
     * @return mixed
     */
    public function getStartAt()
    {
        return $this->startAt;
    }

    /**
     * @param mixed $startAt
     */
    public function setStartAt($startAt)
    {
        $this->startAt = $startAt;
    }

    /**
     * @return \DateTime
     */
    public function getEndAt()
    {
        return $this->endAt;
    }

    /**
     * @param \DateTime $endAt
     */
    public function setEndAt($endAt)
    {
        $this->endAt = $endAt;
    }

    /**
     * @return string
     */
    public function getRepeats()
    {
        return $this->repeats;
    }

    /**
     * @param string $repeats
     */
    public function setRepeats($repeats)
    {
        $this->repeats = $repeats;
    }

    /**
     * @return mixed
     */
    public function getRepeatEvery()
    {
        return $this->repeatEvery;
    }

    /**
     * @param mixed $repeatEvery
     */
    public function setRepeatEvery($repeatEvery)
    {
        $this->repeatEvery = $repeatEvery;
    }

    /**
     * @return string
     */
    public function getRepeatOn()
    {
        return $this->repeatOn;
    }

    /**
     * @param string $repeatOn
     */
    public function setRepeatOn($repeatOn)
    {
        $this->repeatOn = $repeatOn;
    }

    /**
     * @return int
     */
    public function getOccurrences()
    {
        return $this->occurrences;
    }

    /**
     * @param int $occurrences
     */
    public function setOccurrences($occurrences)
    {
        $this->occurrences = $occurrences;
    }

    /**
     * @return Document[]|ArrayCollection
     */
    public function getLinkedDocuments()
    {
        return $this->linkedDocuments;
    }

    /**
     * @param Document[]|ArrayCollection $linkedDocuments
     */
    public function setLinkedDocuments($linkedDocuments)
    {
        $this->linkedDocuments = $linkedDocuments;
    }

    public function addLinkedDocument(Document $document)
    {
        $this->linkedDocuments->add($document);
    }

    public function removeLinkedDocuments(Document $document)
    {
        $this->linkedDocuments->removeElement($document);
    }

    /**
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     */
    public function setCustomer(Customer $customer = null)
    {
        $this->customer = $customer;
    }

    /**
     * @return Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param Company $company
     */
    public function setCompany(Company $company)
    {
        $this->company = $company;
    }

    public function hasCustomer()
    {
        return $this->customer != null;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getNextDueDate()
    {
        return $this->nextDueDate;
    }

    public function setNextDueDate($nextDueDate)
    {
        $this->nextDueDate = $nextDueDate;
    }

    public function calculateNextDueDate()
    {
        $totalDocuments = count($this->linkedDocuments);

        if (!$totalDocuments && $this->repeats != self::EVERY_WEEK) {
            $this->nextDueDate = clone $this->startAt;
            return;
        }

        if ($this->occurrences && $totalDocuments == $this->occurrences) {
            $this->nextDueDate = null;
            return;
        }

        $formatDate = sprintf('+%d ', $totalDocuments * $this->repeatEvery);
        switch ($this->repeats) {
            case self::EVERYDAY:
                $formatDate .= 'day';
                break;
            case self::EVERY_WEEK:
                $weekdays = WeekdayHelper::getDaysArray($this->getRepeatOn());
                if (!$weekdays) {
                    $formatDate .= 'week';
                } else {
                    $value = floor($totalDocuments/count($weekdays));
                    $formatDate = sprintf("+%d %s", $value * $this->repeatEvery, $weekdays[$totalDocuments%count($weekdays)]);
                }
                break;
            case self::EVERY_MONTH:
                $formatDate .= 'month';
                break;
            case self::EVERY_YEAR:
                $formatDate .= 'year';
                break;
        }

        $nextDueDate = new \DateTime(sprintf('%s %s', $this->startAt->format('Y-m-d'), $formatDate));

        $this->nextDueDate = ($this->endAt && $nextDueDate >= $this->endAt) ? null : $nextDueDate;
    }
}
