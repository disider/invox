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

use AppBundle\Exception\InvalidDocumentTypeException;
use AppBundle\Model\DocumentType;
use AppBundle\Model\VatGroupCollection;
use AppBundle\Validator\Constraints as AppAssert;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @JMS\ExclusionPolicy("all")
 * @AppAssert\ValidCustomer
 * @AppAssert\ValidDocument
 * @AppAssert\ValidLinkedDocuments
 * @AppAssert\ValidLinkedRecurrence
 */
class Document extends AttachmentContainer implements Taggable
{
    const INCOMING = 'incoming';
    const OUTGOING = 'outgoing';
    const NO_DIRECTION = 'none';

    const PAID = 'paid';
    const UNPAID = 'unpaid';
    const NO_STATUS = 'none';

    /**
     * @JMS\Expose
     * @var int
     */
    private $id;

    /** @var Company */
    private $company;

    /** @var string */
    private $type;

    /** @var string */
    private $title;

    /** @var string */
    private $content;

    /**
     * @Assert\NotBlank(message="error.empty_ref")
     *
     * @var string
     */
    private $ref;

    /** @var string */
    private $internalRef;

    /** @var string */
    private $direction;

    /** @var string */
    private $companyName;

    /** @var string */
    private $companyVatNumber;

    /** @var string */
    private $companyFiscalCode;

    /** @var string */
    private $companyPhoneNumber;

    /** @var string */
    private $companyFaxNumber;

    /** @var string */
    private $companyAddress;

    /** @var string */
    private $companyAddressNotes;

    /** @var string */
    private $companyZipCode;

    /** @var string */
    private $companyCity;

    /** @var string */
    private $companyProvince;

    /** @var Country */
    private $companyCountry;

    /** @var string */
    private $companyLogoUrl;

    /**
     * @Assert\File(
     *     maxSize="2M",
     *     mimeTypes={
     *         "image/png",
     *         "image/jpg",
     *         "image/jpeg",
     *         "image/bmp"
     *     },
     *     mimeTypesMessage="error.invalid_image_type"
     * )
     * @var UploadedFile
     */
    protected $companyLogo;

    /** @var boolean */
    protected $deleteCompanyLogo;

    /** @var Customer */
    private $linkedCustomer;

    /**
     * @var string
     */
    private $customerName;

    /** @var string */
    private $customerVatNumber;

    /** @var string */
    private $customerFiscalCode;

    /** @var string */
    private $customerPhoneNumber;

    /** @var string */
    private $customerFaxNumber;

    /** @var string */
    private $customerAddress;

    /** @var string */
    private $customerAddressNotes;

    /** @var string */
    private $customerZipCode;

    /** @var string */
    private $customerCity;

    /** @var string */
    private $customerProvince;

    /**
     * @var Country
     */
    private $customerCountry;

    /**
     * @Assert\NotBlank(message="error.empty_year")
     *
     * @var int
     */
    private $year;

    /**
     * @Assert\NotBlank(message="error.empty_issued_at")
     *
     * @var \DateTime
     */
    private $issuedAt;

    /** @var \DateTime */
    private $validUntil;

    /** @var string */
    private $subject;

    /**
     * @Assert\NotBlank(message="error.empty_discount")
     * @var float
     */
    private $discount = 0;

    /**
     * @var bool
     */
    private $discountPercent = false;

    /**
     * @Assert\NotBlank(message="error.empty_rounding")
     * @var float
     */
    private $rounding = 0;

    /** @var float */
    private $netTotal = 0;

    /** @var float */
    private $taxes = 0;

    /**
     * @JMS\Expose()
     * @var float
     */
    private $grossTotal = 0;

    /** @var string */
    private $notes;

    /**
     * @Assert\Valid
     *
     * @var ArrayCollection
     */
    private $rows;

    /**
     * @JMS\Expose()
     * @var ArrayCollection
     */
    private $pettyCashNotes;

    /** @var boolean */
    private $showTotals = true;

    /** @var PaymentType */
    private $paymentType;

    /** @var string */
    private $language = 'en';

    /** @var Document */
    private $linkedOrder;

    /** @var ArrayCollection */
    private $linkedDocuments;

    /** @var string */
    private $status;

    /**
     * @var string
     */
    private $costCenters;

    /**
     * @var boolean
     */
    private $selfInvoice = false;

    /**
     * @var Document
     */
    private $linkedCreditNote;

    /**
     * @var Document
     */
    private $linkedInvoice;

    /** @var DocumentTemplatePerCompany */
    private $documentTemplate;

    /**
     * @var bool
     */
    private $addNewCustomer = false;

    /** @var Recurrence */
    private $recurrence;

    public static function create(
        $type,
        $direction,
        Company $company,
        $number,
        $year,
        \DateTime $issuedAt,
        $language = 'en'
    )
    {
        $entity = new self();

        if (!in_array($type, DocumentType::getAll())) {
            throw new InvalidDocumentTypeException($type);
        }

        if (!in_array($direction, [self::NO_DIRECTION, self::INCOMING, self::OUTGOING])) {
            throw new \LogicException('Invalid document direction: ' . $direction);
        }

        $entity->type = $type;
        $entity->direction = $direction;
        $entity->company = $company;
        $entity->companyName = $company->getName();
        $entity->companyAddress = $company->getAddress();
        $entity->companyVatNumber = $company->getVatNumber();
        $entity->companyFiscalCode = $company->getFiscalCode();
        $entity->ref = $number;
        $entity->year = $year;
        $entity->issuedAt = $issuedAt;

        $entity->language = $language;

        return $entity;
    }

    public static function createEmpty($type, $direction, $number, $year, Company $company = null)
    {
        $entity = new self();

        if (!in_array($type, DocumentType::getAll())) {
            throw new InvalidDocumentTypeException($type);
        }

        if (!in_array($direction, [self::NO_DIRECTION, self::INCOMING, self::OUTGOING])) {
            throw new \LogicException('Invalid document direction: ' . $direction);
        }

        $entity->type = $type;
        $entity->direction = $direction;
        $entity->ref = $number;
        $entity->year = $year;
        $entity->issuedAt = new \DateTime();

        $entity->company = $company;
        $entity->addRow(DocumentRow::createEmpty());

        if ($company) {
            $entity->copyCompanyDetails();
        }

        return $entity;
    }

    public static function createForTestingTemplates(Company $company, DocumentTemplatePerCompany $documentTemplatePerCompany, $locale)
    {
        $document = Document::create(DocumentType::INVOICE, Document::INCOMING, $company, '123', date('Y'), new \DateTime(), $locale);

        $document->setDocumentTemplate($documentTemplatePerCompany);

        $taxRate1 = TaxRate::create('VAT 10%', 10);
        $row1 = DocumentRow::create(1, 0, 'First product', '', 100, 1, $taxRate1, 0);
        $document->addRow($row1);

        $taxRate2 = TaxRate::create('VAT 20%', 20);
        $row2 = DocumentRow::create(1, 1, 'Second product', '', 200, 2, $taxRate2, 20);
        $document->addRow($row2);

        $document->setTitle('My invoice');
        $document->setContent('<p>Some notes here</p>');

        $customer = Customer::create($company, 'Acme', 'adam@acme.com', '01234567890', '09876543210', $company->getCountry(), 'EN', 'London', '01234', 'Abbey Road, 123', '');
        $document->setLinkedCustomer($customer);
        $document->copyCustomerDetails();
        $document->copyCompanyDetails();
        $document->setDiscount(2);
        $document->setRounding(2);
        $document->calculateTotals();

        return $document;
    }

    public function __construct()
    {
        parent::__construct();

        $this->direction = self::NO_DIRECTION;
        $this->status = self::NO_STATUS;
        $this->rows = new ArrayCollection();
        $this->pettyCashNotes = new ArrayCollection();
        $this->linkedDocuments = new ArrayCollection();
        $this->costCenters = new ArrayCollection();
    }

    public function __toString()
    {
        return (string)$this->formatRef();
    }

    public function updateDirection()
    {
        if (!($this->is(DocumentType::INVOICE) || $this->is(DocumentType::CREDIT_NOTE) || $this->is(DocumentType::RECEIPT))) {
            $this->direction = self::NO_DIRECTION;
        }
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    /** @return string */
    public function getId()
    {
        return $this->id;
    }

    /** @return string */
    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function setCompany($company)
    {
        $this->company = $company;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getInternalRef()
    {
        return $this->internalRef;
    }

    public function setInternalRef($internalRef)
    {
        $this->internalRef = $internalRef;
    }

    public function getDirection()
    {
        return $this->direction;
    }

    public function hasDirection()
    {
        return $this->getDirection() && ($this->getDirection() !== self::NO_DIRECTION);
    }

    public function setDirection($direction)
    {
        $this->direction = $direction;
    }

    public function getCompanyName()
    {
        return $this->companyName;
    }

    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;
    }

    public function getCompanyVatNumber()
    {
        return $this->companyVatNumber;
    }

    public function setCompanyVatNumber($companyVatNumber)
    {
        $this->companyVatNumber = $companyVatNumber;
    }

    public function getCompanyFiscalCode()
    {
        return $this->companyFiscalCode;
    }

    public function setCompanyFiscalCode($companyFiscalCode)
    {
        $this->companyFiscalCode = $companyFiscalCode;
    }

    public function getCompanyPhoneNumber()
    {
        return $this->companyPhoneNumber;
    }

    public function setCompanyPhoneNumber($companyPhoneNumber)
    {
        $this->companyPhoneNumber = $companyPhoneNumber;
    }

    public function getCompanyFaxNumber()
    {
        return $this->companyFaxNumber;
    }

    public function setCompanyFaxNumber($companyFaxNumber)
    {
        $this->companyFaxNumber = $companyFaxNumber;
    }

    public function getCompanyAddress()
    {
        return $this->companyAddress;
    }

    public function setCompanyAddress($companyAddress)
    {
        $this->companyAddress = $companyAddress;
    }

    public function getCompanyAddressNotes()
    {
        return $this->companyAddressNotes;
    }

    public function setCompanyAddressNotes($companyAddressNotes)
    {
        $this->companyAddressNotes = $companyAddressNotes;
    }

    public function getCompanyZipCode()
    {
        return $this->companyZipCode;
    }

    public function setCompanyZipCode($companyZipCode)
    {
        $this->companyZipCode = $companyZipCode;
    }

    public function getCompanyCity()
    {
        return $this->companyCity;
    }

    public function setCompanyCity($companyCity)
    {
        $this->companyCity = $companyCity;
    }

    public function getCompanyProvince()
    {
        return $this->companyProvince;
    }

    public function setCompanyProvince($companyProvince)
    {
        $this->companyProvince = $companyProvince;
    }

    public function getCompanyCountry()
    {
        return $this->companyCountry;
    }

    public function setCompanyCountry(Country $companyCountry)
    {
        $this->companyCountry = $companyCountry;
    }

    public function getCompanyLogoUrl()
    {
        return $this->companyLogoUrl;
    }

    public function setCompanyLogoUrl($companyLogoUrl)
    {
        $this->companyLogoUrl = $companyLogoUrl;
    }

    public function hasCompanyLogoUrl()
    {
        return $this->getCompanyLogoUrl() != null;
    }

    public function isAddNewCustomer()
    {
        return $this->addNewCustomer;
    }

    public function setAddNewCustomer($addNewCustomer)
    {
        $this->addNewCustomer = $addNewCustomer;
    }

    public function getLinkedCustomer()
    {
        return $this->linkedCustomer;
    }

    public function setLinkedCustomer(Customer $linkedCustomer = null)
    {
        $this->linkedCustomer = $linkedCustomer;
    }

    public function hasLinkedCustomer()
    {
        return $this->linkedCustomer != null;
    }

    public function getCustomerName()
    {
        return $this->customerName;
    }

    public function setCustomerName($customerName)
    {
        $this->customerName = $customerName;
    }

    public function getCustomerVatNumber()
    {
        return $this->customerVatNumber;
    }

    public function setCustomerVatNumber($customerVatNumber)
    {
        $this->customerVatNumber = $customerVatNumber;
    }

    public function getCustomerFiscalCode()
    {
        return $this->customerFiscalCode;
    }

    public function setCustomerFiscalCode($customerFiscalCode)
    {
        $this->customerFiscalCode = $customerFiscalCode;
    }

    public function getCustomerPhoneNumber()
    {
        return $this->customerPhoneNumber;
    }

    public function setCustomerPhoneNumber($customerPhoneNumber)
    {
        $this->customerPhoneNumber = $customerPhoneNumber;
    }

    public function getCustomerFaxNumber()
    {
        return $this->customerFaxNumber;
    }

    public function setCustomerFaxNumber($customerFaxNumber)
    {
        $this->customerFaxNumber = $customerFaxNumber;
    }

    public function getCustomerAddress()
    {
        return $this->customerAddress;
    }

    public function setCustomerAddress($customerAddress)
    {
        $this->customerAddress = $customerAddress;
    }

    public function getCustomerAddressNotes()
    {
        return $this->customerAddressNotes;
    }

    public function setCustomerAddressNotes($customerAddressNotes)
    {
        $this->customerAddressNotes = $customerAddressNotes;
    }

    public function getCustomerZipCode()
    {
        return $this->customerZipCode;
    }

    public function setCustomerZipCode($customerZipCode)
    {
        $this->customerZipCode = $customerZipCode;
    }

    public function getCustomerCity()
    {
        return $this->customerCity;
    }

    public function setCustomerCity($customerCity)
    {
        $this->customerCity = $customerCity;
    }

    public function getCustomerProvince()
    {
        return $this->customerProvince;
    }

    public function setCustomerProvince($customerProvince)
    {
        $this->customerProvince = $customerProvince;
    }

    public function getCustomerCountry()
    {
        return $this->customerCountry;
    }

    public function setCustomerCountry(Country $customerCountry = null)
    {
        $this->customerCountry = $customerCountry;
    }

    public function getRef()
    {
        return $this->ref;
    }

    public function setRef($ref)
    {
        $this->ref = $ref;
    }

    public function getYear()
    {
        return $this->year;
    }

    public function setYear($year)
    {
        $this->year = $year;
    }

    public function getIssuedAt()
    {
        return $this->issuedAt;
    }

    public function setIssuedAt(\DateTime $issuedAt = null)
    {
        $this->issuedAt = $issuedAt;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function getDiscount()
    {
        return $this->discount;
    }

    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    public function isDiscountPercent()
    {
        return $this->discountPercent;
    }

    public function setDiscountPercent($discountPercent)
    {
        $this->discountPercent = $discountPercent;
    }

    public function getRounding()
    {
        return $this->rounding;
    }

    public function setRounding($rounding)
    {
        $this->rounding = $rounding;
    }

    public function getNotes()
    {
        return $this->notes;
    }

    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    public function getNetTotal()
    {
        return $this->netTotal;
    }

    public function setNetTotal($netTotal)
    {
        $this->netTotal = $netTotal;
    }

    public function getTaxes()
    {
        return $this->taxes;
    }

    public function setTaxes($taxes)
    {
        $this->taxes = $taxes;
    }

    public function getGrossTotal()
    {
        return $this->grossTotal;
    }

    public function setGrossTotal($grossTotal)
    {
        $this->grossTotal = $grossTotal;
    }

    public function isShowTotals()
    {
        return $this->showTotals;
    }

    public function setShowTotals($showTotals)
    {
        $this->showTotals = $showTotals;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function setLanguage($language)
    {
        $this->language = $language;
    }

    public function hasLinkedOrder()
    {
        return $this->linkedOrder != null;
    }

    public function getRows()
    {
        return $this->rows;
    }

    public function setRows($rows)
    {
        $this->rows = $rows;
    }

    public function getRow($index)
    {
        return $this->rows->get($index);
    }

    public function countRows()
    {
        return $this->rows->count();
    }

    public function addRow(DocumentRow $row)
    {
        if (!$this->rows->contains($row)) {
            $this->rows->add($row);
            $row->setDocument($this);
        }
    }

    public function removeRow(DocumentRow $row)
    {
        $this->rows->removeElement($row);
    }

    public function getCostCenters()
    {
        return $this->costCenters;
    }

    public function setCostCenters($costCenters)
    {
        $costCenters = preg_split('/\s*,\s*/', trim($costCenters), null, PREG_SPLIT_NO_EMPTY);

        $costCentersToRemove = [];

        foreach ($this->getCostCenters() as $costCenter) {
            $found = false;

            $i = 0;

            foreach ($costCenters as $i => $name) {
                if ($costCenter->getName() == $name) {
                    $found = true;
                }
                break;
            }

            if ($found) {
                unset($costCenters[$i]);
            } else {
                $costCentersToRemove[] = $costCenter;
            }
        }

        foreach ($costCenters as $name) {
            $costCenter = new DocumentCostCenter();
            $costCenter->setName($name);
            $costCenter->setTaggable($this);
            $this->costCenters->add($costCenter);
        }

        foreach ($costCentersToRemove as $tag) {
            $this->costCenters->removeElement($tag);
        }
    }

    public function isSelfInvoice()
    {
        return $this->selfInvoice;
    }

    public function setSelfInvoice($selfInvoice)
    {
        $this->selfInvoice = $selfInvoice;
    }

    public function getLinkedCreditNote()
    {
        return $this->linkedCreditNote;
    }

    public function hasLinkedCreditNote()
    {
        return $this->linkedCreditNote != null;
    }

    public function setLinkedCreditNote(Document $linkedCreditNote = null)
    {
        $this->linkedCreditNote = $linkedCreditNote;
    }

    public function getLinkedCreditNoteTitle()
    {
        return $this->linkedCreditNote;
    }

    public function setLinkedCreditNoteTitle($title)
    {
    }

    public function getRecurrenceTitle()
    {
        return $this->recurrence;
    }

    public function setRecurrenceTitle($title)
    {
    }

    public function getLinkedInvoice()
    {
        return $this->linkedInvoice;
    }

    public function hasLinkedInvoice()
    {
        return $this->linkedInvoice != null;
    }

    public function setLinkedInvoice(Document $linkedInvoice = null)
    {
        if ($linkedInvoice == $this->linkedInvoice) {
            return;
        }

        if ($this->linkedInvoice) {
            $linkedInvoice->setLinkedCreditNote(null);
        }

        if ($linkedInvoice) {
            $linkedInvoice->setLinkedCreditNote($this);
        }

        $this->linkedInvoice = $linkedInvoice;
    }

    public function getLinkedInvoiceTitle()
    {
        return $this->linkedInvoice;
    }

    public function setLinkedInvoiceTitle($title)
    {
    }

    public function getLinkedOrder()
    {
        return $this->linkedOrder;
    }

    public function getLinkedOrderTitle()
    {
        return $this->linkedOrder;
    }

    public function setLinkedOrderTitle($title)
    {
    }

    public function setLinkedOrder($linkedOrder)
    {
        $this->linkedOrder = $linkedOrder;
    }

    public function getLinkedDocuments()
    {
        return $this->linkedDocuments;
    }

    public function setLinkedDocuments($linkedDocuments)
    {
        $this->linkedDocuments = $linkedDocuments;
    }

    /**
     * @return DocumentTemplatePerCompany
     */
    public function getDocumentTemplate()
    {
        return $this->documentTemplate;
    }

    public function setDocumentTemplate(DocumentTemplatePerCompany $documentTemplate)
    {
        $this->documentTemplate = $documentTemplate;
        $documentTemplate->addDocument($this);
    }

    public function formatRef($separator = '/')
    {
        if ($this->isIncoming()) {
            return $this->ref;
        }

        return sprintf('%03s%s%02d', $this->ref, $separator, substr($this->year, 2));
    }

    public function calculateTotals()
    {
//        dump(microtime() . ' - Document: ' . $this->getRef() . '/' . $this->getYear() . ', rows: ' . count($this->rows));
        /** @var DocumentRow $row */
        foreach ($this->rows as $row) {
            $row->calculateTotals();
        }

        $this->netTotal = $this->evalRows('netCost');
        $this->taxes = $this->evalRows('taxes');
        $this->grossTotal = round($this->calculateDiscount() + $this->getRounding(), 2);

//        dump(sprintf('%s - Row: ' . $this->getRef() . ', net: %s, taxes: %s, gross: %s', microtime(), $this->netTotal, $this->taxes, $this->grossTotal));
    }

    private function evalRows($method)
    {

        $method = 'get' . ucfirst($method);
        $total = 0;

        /** @var DocumentRow $row */
        foreach ($this->rows as $row) {
            $total += $row->$method();
        }

        return $total;
    }

    public function getVatGroupCollection()
    {
        $collection = new VatGroupCollection();

        /** @var DocumentRow $row */
        foreach ($this->rows as $row) {
            $collection->addRow($row);
        }
        return $collection;
    }

    /**
     * @deprecated
     */
    public function getAvailableAmount()
    {
        return $this->getUnpaidAmount();
    }

    /**
     * @deprecated
     */
    public function hasAvailableAmount()
    {
        return $this->hasUnpaidAmount();
    }

    public function is($type)
    {
        return $this->type == $type;
    }

    public function setValidUntil(\DateTime $validUntil = null)
    {
        $this->validUntil = $validUntil;
    }

    public function getValidUntil()
    {
        return $this->validUntil;
    }

    public function getShowTotals()
    {
        return $this->showTotals;
    }

    public function setPaymentType(PaymentType $paymentType = null)
    {
        $this->paymentType = $paymentType;
    }

    public function getPaymentType()
    {
        return $this->paymentType;
    }

    public function getPettyCashNotes()
    {
        return $this->pettyCashNotes;
    }

    public function hasPettyCashNotes()
    {
        return $this->pettyCashNotes->count() > 0;
    }

    public function setPettyCashNotes($pettyCashNotes)
    {
        $this->pettyCashNotes = $pettyCashNotes;
    }

    public function getUploadDir()
    {
        return $this->getCompany()->getUploadDir();
    }

    public function getAttachmentsUploadDir()
    {
        return $this->getUploadDir() . sprintf('/documents/%s/attachments', $this->getId());
    }

    public function hasSameCompanyAs($order)
    {
        return $this->company->isSameAs($order->getCompany());
    }

    public function canAcceptOrder()
    {
        return in_array($this->type, [
            DocumentType::INVOICE,
            DocumentType::CREDIT_NOTE
        ]);
    }

    public function copy()
    {
        $clone = clone $this;
        $clone->rows = new ArrayCollection;
        $clone->attachments = new ArrayCollection;

        /** @var DocumentRow $rows */
        foreach ($this->getRows() as $row) {
            $clonedRow = $row->copy();

            $clone->addRow($clonedRow);
        }

        /** @var Attachment $attachment */
        foreach ($this->getAttachments() as $attachment) {
            $clonedAttachment = $attachment->copy();

            $clone->addAttachment($clonedAttachment);
        }

        return $clone;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function hasStatus()
    {
        return $this->status != self::NO_STATUS;
    }

    public function updateStatus()
    {
        if ($this->is(DocumentType::INVOICE)) {
            $this->status = ($this->isSelfInvoice() || !$this->hasUnpaidAmount()) ? self::PAID : self::UNPAID;

//            dump(sprintf('Updating status for: %s (%s): %s', $this->formatRef(), $this->getType(), $this->getStatus()));
//            dump(sprintf('Total: %f, Available: %f', $this->getGrossTotal(), $this->getUnpaidAmount()));
        } elseif ($this->is(DocumentType::ORDER)) {
            $grossAmount = 0;

            /** @var Document $document */
            foreach ($this->linkedDocuments as $document) {
                $grossAmount += $document->getPaidAmount();
            }

            $this->status = $this->getGrossTotal() == $grossAmount ? self::PAID : self::UNPAID;
        }
    }

    public function addPettyCashNote($pettyCashNote)
    {
        $this->pettyCashNotes->add($pettyCashNote);
    }

    public function hasUnpaidAmountExcept(PettyCashNote $pettyCashNote)
    {
        return $this->getUnpaidAmountExcept($pettyCashNote) > 0;
    }

    public function removePettyCashNote(PettyCashNote $pettyCashNote)
    {
        /** @var InvoicePerNote $note */
        foreach ($this->pettyCashNotes as $note) {
            if ($pettyCashNote->isSameAs($note->getNote())) {
                $this->pettyCashNotes->removeElement($note);
            }
        }
    }

    public function hasValidUntil()
    {
        return $this->getValidUntil();
    }

    public function hasPaymentType()
    {
        return $this->getPaymentType();
    }

    /**
     * @return Recurrence
     */
    public function getRecurrence()
    {
        return $this->recurrence;
    }

    /**
     * @param Recurrence $recurrence
     */
    public function setRecurrence(Recurrence $recurrence = null)
    {
        $this->recurrence = $recurrence;
    }

    public function updateRecurrence()
    {
        if ($this->recurrence != null)
            $this->recurrence->calculateNextDueDate();
    }

    public function hasRecurrence()
    {
        return $this->recurrence != null;
    }

    protected function buildAttachment()
    {
        return new DocumentAttachment();
    }

    public function getCompanyLogo()
    {
        return $this->companyLogo;
    }

    public function setCompanyLogo(UploadedFile $companyLogo = null)
    {
        $this->companyLogo = $companyLogo;

        if ($this->companyLogo) {
            $filename = sha1(uniqid(mt_rand(), true));

            $this->companyLogoUrl = $filename . '.' . $this->companyLogo->guessExtension();
        } else {
            $this->companyLogoUrl = null;
        }
    }

    public function isDeleteCompanyLogo()
    {
        return $this->deleteCompanyLogo;
    }

    public function setDeleteCompanyLogo($deleteCompanyLogo)
    {
        if ($deleteCompanyLogo) {
            $this->companyLogoUrl = null;
        }
    }

    public function onUpload()
    {
        if ($this->companyLogo) {
            $this->companyLogo->move($this->getUploadRootDir(), $this->companyLogoUrl);
            $this->companyLogo = null;
        }
    }

    public function getCompanyLogoWebPath()
    {
        return null == $this->companyLogoUrl
            ? ""
            : $this->getUploadDir() . '/' . $this->companyLogoUrl;
    }

    public function copyCompanyDetails()
    {
        $this->companyName = $this->company->getName();
        $this->companyVatNumber = $this->company->getVatNumber();
        $this->companyFiscalCode = $this->company->getFiscalCode();
        $this->companyPhoneNumber = $this->company->getPhoneNumber();
        $this->companyFaxNumber = $this->company->getFaxNumber();
        $this->companyAddress = $this->company->getAddress();
        $this->companyZipCode = $this->company->getZipCode();
        $this->companyCity = $this->company->getCity();
        $this->companyProvince = $this->company->getProvince();
        $this->companyAddressNotes = $this->company->getAddressNotes();
        $this->companyLogoUrl = $this->company->getLogoUrl();
        $this->companyCountry = $this->company->getCountry();
    }

    public function haveChangedCompanyDetails()
    {
        return $this->companyName != $this->company->getName()
            || $this->companyVatNumber != $this->company->getVatNumber()
            || $this->companyFiscalCode != $this->company->getFiscalCode()
            || $this->companyPhoneNumber != $this->company->getPhoneNumber()
            || $this->companyFaxNumber != $this->company->getFaxNumber()
            || $this->companyAddress != $this->company->getAddress()
            || $this->companyZipCode != $this->company->getZipCode()
            || $this->companyCity != $this->company->getCity()
            || $this->companyProvince != $this->company->getProvince()
            || $this->companyAddressNotes != $this->company->getAddressNotes()
            || $this->companyLogoUrl != $this->company->getLogoUrl()
            || $this->companyCountry != $this->company->getCountry();
    }

    public function copyCustomerDetails()
    {
        if ($this->linkedCustomer) {
            $this->customerName = $this->linkedCustomer->getName();
            $this->customerVatNumber = $this->linkedCustomer->getVatNumber();
            $this->customerFiscalCode = $this->linkedCustomer->getFiscalCode();
            $this->customerPhoneNumber = $this->linkedCustomer->getPhoneNumber();
            $this->customerFaxNumber = $this->linkedCustomer->getFaxNumber();
            $this->customerAddress = $this->linkedCustomer->getAddress();
            $this->customerAddressNotes = $this->linkedCustomer->getAddressNotes();
            $this->customerZipCode = $this->linkedCustomer->getZipCode();
            $this->customerCity = $this->linkedCustomer->getCity();
            $this->customerProvince = $this->linkedCustomer->getProvince();
            $this->customerCountry = $this->linkedCustomer->getCountry();
        }
    }

    public function buildCustomer()
    {
        $customer = Customer::create($this->getCompany(),
            $this->customerName, '',
            $this->customerVatNumber,
            $this->customerFiscalCode,
            $this->customerCountry,
            $this->customerProvince,
            $this->customerCity,
            $this->customerZipCode,
            $this->customerAddress,
            $this->customerAddressNotes);
        $customer->setPhoneNumber($this->customerPhoneNumber);
        $customer->setFaxNumber($this->customerFaxNumber);

        return $customer;
    }

    /**
     * @JMS\VirtualProperty
     */
    public function getFullTitle()
    {
        if (!empty($this->title)) {
            return sprintf('%s - %s', $this->formatRef(), $this->title);
        }

        return $this->formatRef();
    }

    /**
     * @JMS\VirtualProperty
     */
    public function getFullRef()
    {
        return $this->formatRef();
    }

    public function getPaidAmount()
    {
        return $this->getGrossTotal() - $this->getUnpaidAmount();
    }

    /**
     * @JMS\VirtualProperty
     */
    public function getUnpaidAmount()
    {
        $amount = 0;

        /** @var InvoicePerNote $note */
        foreach ($this->getPettyCashNotes() as $note) {
            $amount += $note->getAmount();
        }

        return $this->getUnpaidExceptCreditNotes($amount);
    }

    public function getUnpaidAmountExcept(PettyCashNote $pettyCashNote)
    {
        $amount = 0;

        /** @var InvoicePerNote $note */
        foreach ($this->getPettyCashNotes() as $note) {
            if (!$pettyCashNote->isSameAs($note->getNote())) {
                $amount += $note->getAmount();
            }
        }

        return $this->getUnpaidExceptCreditNotes($amount);
    }

    public function hasUnpaidAmount()
    {
        return $this->getUnpaidAmount() > 0;
    }

    public function isIncoming()
    {
        return $this->getDirection() == self::INCOMING;
    }

    public function isOutgoing()
    {
        return $this->getDirection() == self::OUTGOING;
    }

    /**
     * @param $amount
     * @return float
     */
    protected function getUnpaidExceptCreditNotes($amount)
    {
        $amount = round($amount, 2);

        $unpaidAmount = $this->grossTotal - $amount;

        if ($this->hasLinkedCreditNote()) {
//            dump('Linked gross:' . $this->getLinkedCreditNote()->getGrossTotal() . ' current gross: ' . $this->grossTotal. ' rows: ' . $amount);
            $unpaidAmount -= $this->linkedCreditNote->getGrossTotal();
        }

        return $unpaidAmount;
    }

    public function isUnpaid()
    {
        return $this->status == self::UNPAID;
    }

    public function hasInfo()
    {
        return $this->title || $this->notes || $this->internalRef;
    }

    private function calculateDiscount()
    {
        $taxedNet = $this->netTotal + $this->taxes;

        if ($this->isDiscountPercent()) {
            return $taxedNet - ($taxedNet * $this->getDiscount()) / 100;
        }

        return $taxedNet - $this->getDiscount();
    }
}
