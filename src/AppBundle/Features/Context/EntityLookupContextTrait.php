<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Features\Context;

use AppBundle\Entity\Account;
use AppBundle\Entity\City;
use AppBundle\Entity\Company;
use AppBundle\Entity\Country;
use AppBundle\Entity\Customer;
use AppBundle\Entity\Document;
use AppBundle\Entity\DocumentTemplate;
use AppBundle\Entity\DocumentTemplatePerCompany;
use AppBundle\Entity\Invite;
use AppBundle\Entity\Medium;
use AppBundle\Entity\Page;
use AppBundle\Entity\ParagraphTemplate;
use AppBundle\Entity\PaymentType;
use AppBundle\Entity\PettyCashNote;
use AppBundle\Entity\Product;
use AppBundle\Entity\Province;
use AppBundle\Entity\Recurrence;
use AppBundle\Entity\Service;
use AppBundle\Entity\TaxRate;
use AppBundle\Entity\User;
use AppBundle\Entity\WorkingNote;
use AppBundle\Entity\ZipCode;
use Diside\BehatExtension\Helper\EntityLookup;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

trait EntityLookupContextTrait
{

    public function getExpressionLanguage()
    {
        $language = new ExpressionLanguage();

        $language->register('date', function ($format, $relative = '') {
            return sprintf('(date(\'%s\', strtotime(\'now %s\'))', $format, $relative);
        }, function (array $values, $format, $relative = '') {
            return date($format, strtotime('now ' . $relative));
        });

        return $language;
    }

    protected function getAccountRepository()
    {
        return $this->getRepository(Account::class);
    }

    protected function getCityRepository()
    {
        return $this->getRepository(City::class);
    }

    protected function getCompanyRepository()
    {
        return $this->getRepository(Company::class);
    }

    protected function getCountryRepository()
    {
        return $this->getRepository(Country::class);
    }

    protected function getCustomerRepository()
    {
        return $this->getRepository(Customer::class);
    }

    protected function getDocumentRepository()
    {
        return $this->getRepository(Document::class);
    }

    protected function getDocumentTemplateRepository()
    {
        return $this->getRepository(DocumentTemplate::class);
    }

    protected function getDocumentTemplatePerCompanyRepository()
    {
        return $this->getRepository(DocumentTemplatePerCompany::class);
    }

    protected function getInviteRepository()
    {
        return $this->getRepository(Invite::class);
    }

    protected function getMediumRepository()
    {
        return $this->getRepository(Medium::class);
    }

    protected function getPageRepository()
    {
        return $this->getRepository(Page::class);
    }

    protected function getPaymentTypeRepository()
    {
        return $this->getRepository(PaymentType::class);
    }

    protected function getParagraphTemplateRepository()
    {
        return $this->getRepository(ParagraphTemplate::class);
    }

    protected function getPettyCashNoteRepository()
    {
        return $this->getRepository(PettyCashNote::class);
    }

    protected function getProductRepository()
    {
        return $this->getRepository(Product::class);
    }

    protected function getProvinceRepository()
    {
        return $this->getRepository(Province::class);
    }

    protected function getRecurrenceRepository()
    {
        return $this->getRepository(Recurrence::class);
    }

    protected function getServiceRepository()
    {
        return $this->getRepository(Service::class);
    }

    protected function getTaxRateRepository()
    {
        return $this->getRepository(TaxRate::class);
    }

    protected function getUserRepository()
    {
        return $this->getRepository(User::class);
    }

    protected function getWorkingNoteRepository()
    {
        return $this->getRepository(WorkingNote::class);
    }

    protected function getZipCodeRepository()
    {
        return $this->getRepository(ZipCode::class);
    }

    public function getEntityLookupTables()
    {
        return [
            'accounts' => new EntityLookup($this->getAccountRepository(), 'name'),
            'cities' => new EntityLookup($this->getCityRepository(), 'name'),
            'companies' => new EntityLookup($this->getCompanyRepository(), 'name'),
            'countries' => new EntityLookup($this->getCountryRepository(), 'code'),
            'customers' => new EntityLookup($this->getCustomerRepository(), 'name'),
            'documents' => new EntityLookup($this->getDocumentRepository(), 'ref'),
            'documentTemplates' => new EntityLookup($this->getDocumentTemplateRepository(), 'name'),
            'documentTemplatesPerCompany' => new EntityLookup($this->getDocumentTemplatePerCompanyRepository(), 'name'),
            'invites' => new EntityLookup($this->getInviteRepository(), 'token'),
            'media' => new EntityLookup($this->getMediumRepository(), 'fileName'),
            'pages' => new EntityLookup($this->getPageRepository(), 'title'),
            'paragraphTemplates' => new EntityLookup($this->getParagraphTemplateRepository(), 'title'),
            'paymentTypes' => new EntityLookup($this->getPaymentTypeRepository(), 'days'),
            'pettyCashNotes' => new EntityLookup($this->getPettyCashNoteRepository(), 'ref'),
            'products' => new EntityLookup($this->getProductRepository(), 'code'),
            'provinces' => new EntityLookup($this->getProvinceRepository(), 'code'),
            'recurrences' => new EntityLookup($this->getRecurrenceRepository(), 'content'),
            'services' => new EntityLookup($this->getServiceRepository(), 'code'),
            'taxRates' => new EntityLookup($this->getTaxRateRepository(), 'amount'),
            'users' => new EntityLookup($this->getUserRepository(), 'username'),
            'workingNotes' => new EntityLookup($this->getWorkingNoteRepository(), 'code'),
            'zipCodes' => new EntityLookup($this->getZipCodeRepository(), 'code'),
        ];
    }
}
