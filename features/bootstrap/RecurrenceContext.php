<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

use App\Entity\Company;
use App\Entity\Customer;
use App\Entity\Recurrence;
use Behat\Gherkin\Node\TableNode;

class RecurrenceContext extends BaseMinkContext
{
    /**
     * @Given /^there is a recurrence:$/
     * @Given /^there are recurrences:$/
     */
    public function thereAreRecurrences(TableNode $table)
    {
        $this->buildEntities($table);
    }

    /**
     * @Then /^I should see (\d+) recurrences$/
     * @Then /^I should see (\d+) recurrence$/
     */
    public function iSeeRecurrences($number)
    {
        $this->assertNumElements($number, '.recurrence');
    }

    private function buildEntities(TableNode $table)
    {
        foreach ($table->getHash() as $key => $values) {
            $entity = $this->buildEntity(
                $values['company'],
                $this->getValue($values, 'customer'),
                $this->getValue($values, 'direction', Recurrence::OUTGOING),
                $this->getValue($values, 'content'),
                $this->getValue($values, 'repeats', Recurrence::EVERYDAY),
                $this->getValue($values, 'startAt', '10/12/2016'),
                $this->getValue($values, 'repeatEvery', 1),
                $this->getValue($values, 'repeatOn'),
                $this->getValue($values, 'occurrences'),
                $this->getValue($values, 'amount', 10),
                $this->getValue($values, 'endAt')
            );

            $this->getRecurrenceRepository()->save($entity);
        }
    }

    private function buildEntity(
        $companyName,
        $customerName,
        $direction,
        $content,
        $repeats,
        $startAt,
        $repeatEvery,
        $repeatOn,
        $occurrences,
        $amount,
        $endAt
    ) {
        /** @var Company $company */
        $company = $this->getCompanyRepository()->findOneByName($companyName);

        /** @var Customer $customer */
        $customer = $this->getCustomerRepository()->findOneByName($customerName);

        $recurrence = new Recurrence();
        $recurrence->setCompany($company);
        $recurrence->setCustomer($customer);
        $recurrence->setDirection($direction);
        $recurrence->setContent($content);
        $recurrence->setRepeats($repeats);
        $recurrence->setStartAt(new \DateTime($startAt));
        $recurrence->setRepeatEvery($repeatEvery);
        $recurrence->setRepeatOn($repeatOn);
        $recurrence->setOccurrences($occurrences);
        $recurrence->setAmount($amount);

        if ($endAt) {
            $recurrence->setEndAt(new \DateTime($endAt));
        }

        return $recurrence;
    }

}
