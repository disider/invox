<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Features\App;

use App\Entity\Service;
use App\Entity\ServiceAttachment;
use Behat\Gherkin\Node\TableNode;

class ServiceContext extends AbstractMinkContext
{
    /**
     * @Given /^there is a service:$/
     * @Given /^there are services:$/
     */
    public function thereAreEntities(TableNode $table)
    {
        foreach ($table->getHash() as $key => $values) {
            $entity = $this->buildEntity(
                $values['company'],
                $values['name'],
                $this->getValue($values, 'code', $values['name']),
                $this->getFloatValue($values, 'unitPrice'),
                $this->getValue($values, 'tags', '')
            );

            $taxRateAmount = $this->getFloatValue($values, 'taxRate');

            if ($taxRateAmount) {
                $taxRate = $this->getTaxRateRepository()->findOneByAmount($taxRateAmount);

                if (!$taxRate) {
                    throw new \InvalidArgumentException('No tax rate found with amount: '.$taxRateAmount);
                }

                $entity->setTaxRate($taxRate);
            }

            $this->getServiceRepository()->save($entity);
        }
    }

    /**
     * @Given /^there is a service attachment:$/
     */
    public function thereIsAServiceAttachment(TableNode $table)
    {
        foreach ($table->getHash() as $key => $values) {
            /** @var Service $service */
            $service = $this->getServiceRepository()->findOneByName($values['service']);

            $fileName = $values['fileName'];
            $fileUrl = $values['fileUrl'];

            $this->buildAttachment(
                $service,
                $fileName,
                $fileUrl,
                ServiceAttachment::class
            );

            $this->getServiceRepository()->save($service);
        }
    }

    private function buildEntity($companyName, $name, $code, $unitPrice, $tags)
    {
        $company = $this->getCompanyRepository()->findOneByName($companyName);

        $service = Service::create($company, $name, $code);
        $service->setUnitPrice($unitPrice);
        $service->setTags($tags);

        return $service;
    }
}
