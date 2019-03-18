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

use App\Entity\PaymentType;
use Behat\Gherkin\Node\TableNode;

class PaymentTypeContext extends AbstractMinkContext
{
    /**
     * @Given /^there is a payment type:$/
     * @Given /^there are payment types:$/
     */
    public function thereAreEntities(TableNode $table)
    {
        foreach ($table->getHash() as $key => $values) {
            $entity = $this->buildEntity(
                $key,
                $this->getValue($values, 'name', 'PaymentType'),
                $values['days'],
                $this->getBoolValue($values, 'endOfMonth', false)
            );

            $this->getPaymentTypeRepository()->save($entity);
        }
    }

    private function buildEntity($position, $name, $days, $endOfMonth)
    {
        $paymentType = PaymentType::create($position, $name, $days, $endOfMonth);
        $paymentType->mergeNewTranslations();

        return $paymentType;
    }
}
