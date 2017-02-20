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

use AppBundle\Model\DocumentType;
use AppBundle\Model\Language;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @JMS\ExclusionPolicy("all")
 */
class Customer extends AttachmentContainer
{
    /**
     * @JMS\Expose
     * @var int
     */
    private $id;

    /**
     * @var Company
     */
    private $company;

    /**
     * @Assert\NotBlank(message="error.empty_name")
     *
     * @JMS\Expose
     * @var string
     */
    private $name;

    /**
     * @Assert\NotBlank(message="error.empty_vat_number")
     * @Assert\Regex(pattern="/[0-9]{11}/", message="error.invalid_vat_number")
     *
     * @JMS\Expose
     * @var string
     */
    private $vatNumber;

    /**
     * @JMS\Expose
     * @var string
     */
    private $fiscalCode;

    /**
     * @Assert\Email(message="error.invalid_email")
     *
     * @JMS\Expose
     * @var string
     */
    private $email;

    /** @var string */
    private $referent;

    /**
     * @JMS\Expose
     * @var string
     */
    private $phoneNumber;

    /**
     * @JMS\Expose
     * @var string
     */
    private $faxNumber;

    /**
     * @JMS\Expose
     * @var string
     */
    private $address;

    /**
     * @JMS\Expose
     * @var string
     */
    private $addressNotes;

    /** @var string */
    private $notes;

    /**
     * @JMS\Expose
     * @var string
     */
    private $zipCode;

    /**
     * @JMS\Expose
     * @var string
     */
    private $city;

    /**
     * @JMS\Expose
     * @var string
     */
    private $province;

    /** @var Country */
    private $country;

    /**
     * @JMS\Expose
     * @var string
     */
    private $language;

    /**
     * @var ArrayCollection
     */
    private $documents;

    public static function create(Company $company, $name, $email, $vatNumber, $fiscalCode, Country $country, $province, $city, $zipCode, $address, $addressNotes)
    {
        $entity = new self();

        $entity->name = $name;
        $entity->email = $email;
        $entity->vatNumber = $vatNumber;
        $entity->fiscalCode = $fiscalCode;
        $entity->country = $country;
        $entity->province = $province;
        $entity->city = $city;
        $entity->zipCode = $zipCode;
        $entity->address = $address;
        $entity->addressNotes = $addressNotes;
        $entity->language = Language::ITALIAN;

        $company->addCustomer($entity);

        return $entity;
    }

    public function __construct()
    {
        parent::__construct();

        $this->documents = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    /** @return int */
    public function getId()
    {
        return $this->id;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function setCompany($company)
    {
        $this->company = $company;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getVatNumber()
    {
        return $this->vatNumber;
    }

    public function setVatNumber($vatNumber)
    {
        $this->vatNumber = $vatNumber;
    }

    public function getFiscalCode()
    {
        return $this->fiscalCode;
    }

    public function setFiscalCode($fiscalCode)
    {
        $this->fiscalCode = $fiscalCode;
    }

    public function getReferent()
    {
        return $this->referent;
    }

    public function setReferent($referent)
    {
        $this->referent = $referent;
    }

    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getFaxNumber()
    {
        return $this->faxNumber;
    }

    public function setFaxNumber($faxNumber)
    {
        $this->faxNumber = $faxNumber;
    }

    public function getAddressNotes()
    {
        return $this->addressNotes;
    }

    public function setAddressNotes($addressNotes)
    {
        $this->addressNotes = $addressNotes;
    }

    public function getNotes()
    {
        return $this->notes;
    }

    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function getZipCode()
    {
        return $this->zipCode;
    }

    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city)
    {
        $this->city = $city;
    }

    public function getProvince()
    {
        return $this->province;
    }

    public function setProvince($province)
    {
        $this->province = $province;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry(Country $country = null)
    {
        $this->country = $country;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function setLanguage($language)
    {
        $this->language = $language;
    }

    public function getDocuments()
    {
        return $this->documents;
    }

    public function setDocuments($documents)
    {
        $this->documents = $documents;
    }

    public function isProspect()
    {
        $documents = $this->documents->filter(function (Document $document) {
            return $document->is(DocumentType::QUOTE);
        });

        return count($documents) > 0;
    }

    public function isCustomer()
    {
        $documents = $this->documents->filter(function (Document $document) {
            return $document->isOutgoing();
        });

        return count($documents) > 0;
    }

    public function isSupplier()
    {
        $documents = $this->documents->filter(function (Document $document) {
            return $document->isIncoming();
        });

        return count($documents) > 0;
    }

    /**
     * @JMS\VirtualProperty()
     */
    public function getCountryId()
    {
        return $this->country->getId();
    }

    public function getUploadDir()
    {
        return $this->getCompany()->getUploadDir();
    }

    public function getAttachmentsUploadDir()
    {
        return $this->getUploadDir() . sprintf('/customers/%s/attachments', $this->getId());
    }

    protected function buildAttachment()
    {
        return new CustomerAttachment();
    }
}
