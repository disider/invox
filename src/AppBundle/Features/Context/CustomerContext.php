<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Features\Context;

use AppBundle\Entity\Customer;
use AppBundle\Entity\CustomerAttachment;
use Behat\Gherkin\Node\TableNode;

class CustomerContext extends BaseMinkContext
{
    /**
     * @Given /^there is a customer:$/
     * @Given /^there are customers:$/
     */
    public function thereAreEntities(TableNode $table)
    {
        foreach ($table->getHash() as $key => $values) {
            $entity = $this->buildEntity(
                $values['company'],
                $values['name'],
                $values['email'],
                $this->getValue($values, 'country'),
                $this->getValue($values, 'address', null),
                $this->getValue($values, 'vatNumber', '01234567890')
            );

            $this->getCustomerRepository()->save($entity);
        }
    }

    private function buildEntity($companyName, $name, $email, $countryName, $address, $vatNumber)
    {
        $company = $this->getCompanyRepository()->findOneByName($companyName);

        $country = $this->getCountry($countryName);

        return Customer::create($company, $name, $email, $vatNumber, $vatNumber, $country, 'PR', 'City', '00100', $address, '');
    }

    /**
     * @Given /^there is a customer attachment:$/
     */
    public function thereIsACustomerAttachment(TableNode $table)
    {
        foreach ($table->getHash() as $key => $values) {
            /** @var Customer $customer */
            $customer = $this->getCustomerRepository()->findOneByName($values['customer']);

            $this->buildAttachment(
                $customer,
                $values['fileName'],
                $values['fileUrl'],
                CustomerAttachment::class
            );

            $this->getCustomerRepository()->save($customer);
        }
    }
}
