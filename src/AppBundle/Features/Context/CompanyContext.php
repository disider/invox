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

use AppBundle\Entity\Company;
use AppBundle\Entity\DocumentTemplate;
use AppBundle\Entity\User;
use AppBundle\Model\DocumentType;
use AppBundle\Model\Module;
use Behat\Gherkin\Node\TableNode;

class CompanyContext extends BaseMinkContext
{
    /**
     * @Given /^there is a company:$/
     * @Given /^there are companies:$/
     */
    public function thereAreEntities(TableNode $table)
    {
        foreach ($table->getHash() as $key => $values) {
            $company = $this->buildEntity(
                $values['owner'],
                $this->getValue($values, 'manager'),
                $values['name'],
                $this->getValue($values, 'country'),
                $this->getValue($values, 'address', ''),
                $this->getValue($values, 'vatNumber', ''),
                $this->getValue($values, 'modules', implode(',', Module::getTypes())),
                $this->getValue($values, 'documentTypes', implode(',', DocumentType::getAll()))
            );

            $templates = $this->getDocumentTemplateRepository()->findAll();
            if (!count($templates)) {
                $template = DocumentTemplate::create('Default',
                    '#434343', '#3D81B6', '#fff', '#efefef',
                    'sans-serif', '', '', '');

                $this->getDocumentTemplateRepository()->save($template);
                $company->addDocumentTemplate($template);
            } else {
                foreach ($templates as $template) {
                    $company->addDocumentTemplate($template);
                }
            }

            $this->getCompanyRepository()->save($company);
        }
    }

    /**
     * @Given there are enabled document types:
     */
    public function thereAreEnabledDocumentTypes(TableNode $table)
    {
        foreach ($table->getHash() as $key => $values) {
            $companyName = $values['company'];
            $documentTypes = $values['types'];

            /** @var Company $company */
            $company = $this->getCompanyRepository()->findOneByName($companyName);

            $this->addDocumentTypes($company, $documentTypes);

            $this->getCompanyRepository()->save($company);
        }
    }

    private function buildEntity($ownerEmail, $managerEmail, $name, $countryName, $address, $vatNumber, $modules, $documentTypes)
    {
        /** @var User $owner */
        $owner = $this->getUserRepository()->findOneByEmail($ownerEmail);
        $country = $this->getCountry($countryName);

        $company = Company::create($country, $owner, $name, $address, $vatNumber);

        if ($managerEmail) {
            $manager = $this->getUserRepository()->findOneByEmail($managerEmail);
            $company->addManager($manager);
        }

        foreach (explode(',', $modules) as $module) {
            $company->addModule(new Module(trim($module)));
        }

        $this->addDocumentTypes($company, $documentTypes);

        return $company;
    }

    private function addDocumentTypes(Company $company, $documentTypes)
    {
        $company->setDocumentTypes([]);

        foreach (explode(',', $documentTypes) as $documentType) {
            $documentType = trim($documentType);

            if (!empty($documentType)) {
                $company->addDocumentType($documentType);
            }
        }
    }

}
