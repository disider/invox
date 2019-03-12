<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

use App\Entity\TaxRate;
use Behat\Gherkin\Node\TableNode;

class TaxRateContext extends BaseMinkContext
{
    /**
     * @Given /^there is a tax rate:$/
     * @Given /^there are tax rates:$/
     */
    public function thereAreEntities(TableNode $table)
    {
        foreach ($table->getHash() as $key => $values) {
            $entity = $this->buildEntity(
                $this->getValue($values, 'name', 'TaxRate'),
                $values['amount']
            );

            $this->getTaxRateRepository()->save($entity);
        }
    }

    private function buildEntity($name, $amount)
    {
        $taxRate = TaxRate::create($name, $amount);
        $taxRate->mergeNewTranslations();

        return $taxRate;
    }
}
