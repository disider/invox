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

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

class WorkingNote
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @Assert\NotBlank(message="error.empty_title")
     *
     * @var string
     */
    private $title;

    /**
     * @Assert\NotBlank(message="error.empty_title")
     *
     * @var string
     */
    private $code;

    /**
     * @var Company
     */
    private $company;

    /**
     * @var Customer
     */
    private $customer;

    /**
     * @Assert\Valid
     *
     * @var ArrayCollection
     */
    private $paragraphs;

    public static function create(Company $company, $title, $code)
    {
        $entity = new self();
        $entity->setCompany($company);
        $entity->setTitle($title);
        $entity->setCode($code);

        return $entity;
    }

    public function __construct()
    {
        $this->paragraphs = new ArrayCollection();
    }

    public function __toString()
    {
        return sprintf('%s - %s', $this->code, $this->title);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function setCompany(Company $company)
    {
        $this->company = $company;
    }

    public function addParagraph(Paragraph $paragraph)
    {
        $this->paragraphs->add($paragraph);

        $paragraph->setWorkingNote($this);
    }

    public function removeParagraph(Paragraph $paragraph)
    {
        $this->paragraphs->removeElement($paragraph);
    }

    public function getParagraphs()
    {
        return $this->paragraphs;
    }


    public function setParagraphs($paragraphs)
    {
        $this->paragraphs = $paragraphs;
    }

    public function getCustomer()
    {
        return $this->customer;
    }

    public function setCustomer(Customer $customer = null)
    {
        $this->customer = $customer;
    }

    public function getCustomerName() {
        return $this->customer ? $this->customer->getName() : null;
    }
}
