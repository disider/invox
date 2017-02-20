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

use AppBundle\Exception\InvalidDocumentTypeException;
use AppBundle\Model\DocumentType;
use AppBundle\Model\Module;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class Company extends AttachmentContainer
{
    /** @var int */
    private $id;

    /**
     * @Assert\NotBlank(message="error.empty_name")
     *
     * @var string
     */
    private $name;

    /**
     * @Assert\NotBlank(message="error.empty_vat_number")
     * @Assert\Regex(pattern="/[0-9]{11}/", message="error.invalid_vat_number")
     *
     * @var string
     */
    private $vatNumber;

    /** @var string */
    private $fiscalCode;

    /** @var string */
    private $phoneNumber;

    /** @var string */
    private $faxNumber;

    /** @var string */
    private $address;

    /** @var string */
    private $addressNotes;

    /** @var string */
    private $zipCode;

    /** @var string */
    private $city;

    /** @var string */
    private $province;

    /** @var Country */
    private $country;

    /** @var string */
    private $logoUrl;

    /**
     * @var User
     */
    private $owner;

    /**
     * @var ArrayCollection
     */
    private $managers;

    /**
     * @var ArrayCollection
     */
    private $accounts;

    /**
     * @var ArrayCollection
     */
    private $customers;

    /**
     * @var ArrayCollection
     */
    private $documents;

    /**
     * @var array
     */
    private $documentTypes;

    /**
     * @var ArrayCollection
     */
    private $paymentTypes;

    /**
     * @var ArrayCollection
     */
    private $pettyCashNotes;

    /**
     * @var ArrayCollection
     */
    private $products;

    /**
     * @var ArrayCollection
     */
    private $services;

    /**
     * @var ArrayCollection
     */
    private $workingNotes;

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
    private $logo;

    /** @var boolean */
    private $deleteLogo;

    /**
     * @var User
     */
    private $accountant;

    /**
     * @var array
     */
    protected $modules;

    /** @var ArrayCollection */
    private $documentTemplates;

    /** @var ArrayCollection */
    private $invites;

    /** @var ArrayCollection */
    private $salesAgents;

    /** @return Company */
    public static function create(Country $country, User $owner, $name, $address, $vatNumber)
    {
        $entity = new self();
        $entity->country = $country;
        $entity->name = $name;
        $entity->address = $address;
        $entity->vatNumber = $vatNumber;

        $owner->addOwnedCompany($entity);

        return $entity;
    }

    public function __construct()
    {
        parent::__construct();

        $this->salesAgents = new ArrayCollection();
        $this->managers = new ArrayCollection();
        $this->accounts = new ArrayCollection();
        $this->customers = new ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->paymentTypes = new ArrayCollection();
        $this->pettyCashNotes = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->services = new ArrayCollection();
        $this->workingNotes = new ArrayCollection();

        $this->documentTemplates = new ArrayCollection();
        $this->invites = new ArrayCollection();

        $this->modules = [];
        $this->documentTypes = [];
    }

    public static function createEmpty(User $user, $templates)
    {
        $entity = new self();
        $entity->setOwner($user);
        $entity->addModule(new Module(Module::ACCOUNTS_MODULE));
        $entity->addModule(new Module(Module::PETTY_CASH_NOTES_MODULE));
        $entity->setDocumentTypes(DocumentType::getAll());

        foreach($templates as $template) {
            $entity->addDocumentTemplate($template);
        }

        return $entity;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getOwner()
    {
        return $this->owner;
    }

    public function setOwner(User $owner)
    {
        $this->owner = $owner;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
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

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function getAddressNotes()
    {
        return $this->addressNotes;
    }

    public function setAddressNotes($addressNotes)
    {
        $this->addressNotes = $addressNotes;
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

    public function addAccount(Account $account)
    {
        $this->accounts->add($account);
        $account->setCompany($this);
    }

    public function isSameAs(Company $other)
    {
        return $this->getId() == $other->getId();
    }

    public function addCustomer(Customer $customer)
    {
        $this->customers->add($customer);
        $customer->setCompany($this);
    }

    public function hasManager(User $user)
    {
        /** @var User $manager */
        foreach ($this->managers as $manager) {
            if ($manager->isSameAs($user)) {
                return true;
            }
        }

        return false;
    }

    public function hasSalesAgent(User $user)
    {
        /** @var User $salesAgent */
        foreach ($this->salesAgents as $salesAgent) {
            if ($salesAgent->isSameAs($user)) {
                return true;
            }
        }

        return false;
    }

    public function addManager(User $manager)
    {
        if (!$this->managers->contains($manager)) {
            $this->managers->add($manager);
            $manager->addManagedCompany($this);
        }
    }

    public function removeManager(User $manager)
    {
        if ($this->managers->contains($manager)) {
            $this->managers->removeElement($manager);
            $manager->removeManagedCompany($this);
        }
    }

    public function getManagers()
    {
        return $this->managers;
    }

    public function setManagers($managers)
    {
        $this->managers = $managers;
    }

    public function addSalesAgent(User $user)
    {
        $this->salesAgents->add($user);
    }

    public function removeSalesAgent(User $user)
    {
        $this->salesAgents->removeElement($user);
    }

    public function getSalesAgents()
    {
        return $this->salesAgents;
    }

    public function setSalesAgents($salesAgents)
    {
        $this->salesAgents = $salesAgents;
    }

    public function getAccounts()
    {
        return $this->accounts;
    }

    public function setAccounts($accounts)
    {
        $this->accounts = $accounts;
    }

    public function getCustomers()
    {
        return $this->customers;
    }

    public function setCustomers($customers)
    {
        $this->customers = $customers;
    }

    public function getDocuments()
    {
        return $this->documents;
    }

    public function setDocuments($documents)
    {
        $this->documents = $documents;
    }

    public function getPettyCashNotes()
    {
        return $this->pettyCashNotes;
    }

    public function setPettyCashNotes($pettyCashNotes)
    {
        $this->pettyCashNotes = $pettyCashNotes;
    }

    public function getPaymentTypes()
    {
        return $this->paymentTypes;
    }

    public function setPaymentTypes($paymentTypes)
    {
        $this->paymentTypes = $paymentTypes;
    }

    public function getLogo()
    {
        return $this->logo;
    }

    public function setLogo(UploadedFile $logo = null)
    {
        $this->logo = $logo;

        if ($this->logo) {
            $filename = sha1(uniqid(mt_rand(), true));

            $this->logoUrl = $filename . '.' . $this->logo->guessExtension();
        }
        else {
//            $this->logoToDelete = $this->logoUrl;
            $this->logoUrl = null;
        }
    }

    public function isDeleteLogo()
    {
        return $this->deleteLogo;
    }

    public function setDeleteLogo($deleteLogo)
    {
        if ($deleteLogo) {
            $this->logoUrl = null;
        }
    }

    public function getAccountant()
    {
        return $this->accountant;
    }

    public function setAccountant(User $accountant = null)
    {
        $this->accountant = $accountant;
    }

    public function hasAccountant()
    {
        return $this->accountant != null;
    }

    public function onUpload()
    {
        if ($this->logo) {
            $this->logo->move($this->getUploadRootDir(), $this->logoUrl);
        }
    }

    public function getUploadDir()
    {
        return '/uploads/companies/' . $this->getId();
    }

    public function getUploadRootDir()
    {
        return __DIR__ . '/../../../web/' . $this->getUploadDir();
    }

    public function getLogoWebPath()
    {
        return null == $this->logoUrl
            ? ""
            : $this->getUploadDir() . '/' . $this->logoUrl;
    }

    public function getLogoUrl()
    {
        return $this->logoUrl;
    }

    public function hasLogoUrl()
    {
        return $this->logoUrl != null;
    }

    public function getModules()
    {
        $modules = $this->modules;

        return array_unique($modules);
    }

    public function setModules(array $modules)
    {
        $this->modules = [];

        foreach ($modules as $module) {
            $this->addModule($module);
        }
    }

    public function hasModule(Module $module)
    {
        return in_array($module->getName(), $this->getModules(), true);
    }

    public function addModule($module)
    {
        if (is_string($module)) {
            $module = new Module($module);
        }

        if (!in_array($module->getName(), $this->modules, true)) {
            $this->modules[] = $module->getName();
        }
    }

    public function removeModule(Module $module)
    {
        if (false !== $key = array_search($module->getName(), $this->modules, true)) {
            unset($this->modules[$key]);
            $this->modules = array_values($this->modules);
        }
    }

    /**
     * @return ArrayCollection
     */
    public function getDocumentTemplates()
    {
        return $this->documentTemplates;
    }

    /**
     * @param ArrayCollection $documentTemplates
     */
    public function setDocumentTemplates($documentTemplates)
    {
        $this->documentTemplates = $documentTemplates;
    }

    public function addDocumentTemplatePerCompany(DocumentTemplatePerCompany $documentTemplatePerCompany)
    {
        $this->documentTemplates->add($documentTemplatePerCompany);
    }

    public function addDocumentTemplate(DocumentTemplate $documentTemplate)
    {
        $templatePerCompany = DocumentTemplatePerCompany::create($documentTemplate, $this);
        $templatePerCompany->copyDocumentTemplateDetails();

        $this->addDocumentTemplatePerCompany($templatePerCompany);
        return $templatePerCompany;
    }

    public function getFirstDocumentTemplate()
    {
        if ($this->getDocumentTemplates()->count() == 0) {
            throw new \LogicException('Company has no document templates');
        }

        return $this->documentTemplates->first();
    }

    public function getInvites()
    {
        return $this->invites;
    }

    public function setInvites($invites)
    {
        $this->invites = $invites;
    }

    public function getDocumentTypes()
    {
        return $this->documentTypes;
    }

    public function setDocumentTypes($documentTypes)
    {
        $this->documentTypes = $documentTypes;
    }

    public function addDocumentType($documentType)
    {
        if (!in_array($documentType, DocumentType::getAll())) {
            throw new InvalidDocumentTypeException(sprintf('Unknown document type: %s', $documentType));
        }

        if (!$this->hasDocumentType($documentType)) {
            $this->documentTypes[] = $documentType;
        }
    }

    public function hasDocumentType($documentType)
    {
        return in_array($documentType, $this->documentTypes);
    }

    protected function buildAttachment()
    {
        return new Medium();
    }

    public function getAttachmentsUploadDir()
    {
        return $this->getUploadDir() . '/media';
    }
}
