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
        return $this->getRepository('AppBundle:Account');
    }

    protected function getCityRepository()
    {
        return $this->getRepository('AppBundle:City');
    }

    protected function getCompanyRepository()
    {
        return $this->getRepository('AppBundle:Company');
    }

    protected function getCountryRepository()
    {
        return $this->getRepository('AppBundle:Country');
    }

    protected function getCustomerRepository()
    {
        return $this->getRepository('AppBundle:Customer');
    }

    protected function getDocumentRepository()
    {
        return $this->getRepository('AppBundle:Document');
    }

    protected function getDocumentTemplateRepository()
    {
        return $this->getRepository('AppBundle:DocumentTemplate');
    }

    protected function getDocumentTemplatePerCompanyRepository()
    {
        return $this->getRepository('AppBundle:DocumentTemplatePerCompany');
    }

    protected function getInviteRepository()
    {
        return $this->getRepository('AppBundle:Invite');
    }

    protected function getMediumRepository()
    {
        return $this->getRepository('AppBundle:Medium');
    }

    protected function getPageRepository()
    {
        return $this->getRepository('AppBundle:Page');
    }

    protected function getPaymentTypeRepository()
    {
        return $this->getRepository('AppBundle:PaymentType');
    }

    protected function getParagraphTemplateRepository()
    {
        return $this->getRepository('AppBundle:ParagraphTemplate');
    }

    protected function getPettyCashNoteRepository()
    {
        return $this->getRepository('AppBundle:PettyCashNote');
    }

    protected function getProductRepository()
    {
        return $this->getRepository('AppBundle:Product');
    }

    protected function getProvinceRepository()
    {
        return $this->getRepository('AppBundle:Province');
    }

    protected function getRecurrenceRepository()
    {
        return $this->getRepository('AppBundle:Recurrence');
    }

    protected function getServiceRepository()
    {
        return $this->getRepository('AppBundle:Service');
    }

    protected function getTaxRateRepository()
    {
        return $this->getRepository('AppBundle:TaxRate');
    }

    protected function getUserRepository()
    {
        return $this->getRepository('AppBundle:User');
    }

    protected function getZipCodeRepository()
    {
        return $this->getRepository('AppBundle:ZipCode');
    }

    protected function getWorkingNoteRepository()
    {
        return $this->getRepository('AppBundle:WorkingNote');
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
            'workingNotes' => new EntityLookup($this->get('working_note_repository'), 'code'),
            'zipCodes' => new EntityLookup($this->getZipCodeRepository(), 'code'),
        ];
    }
}
