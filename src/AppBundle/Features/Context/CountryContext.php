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

use AppBundle\Entity\Country;
use Behat\Gherkin\Node\TableNode;

class CountryContext extends BaseMinkContext
{
    /**
     * @Given /^there is a country:$/
     * @Given /^there are countries:$/
     */
    public function thereAreEntities(TableNode $table)
    {
        foreach ($table->getHash() as $key => $values) {
            $entity = $this->buildEntity(
                $values['code'],
                $this->getValue($values, 'name', $values['code'])
            );

            $this->getCountryRepository()->save($entity);
        }
    }

    private function buildEntity($code, $name)
    {
        $country = Country::create($code, $name);
        $country->mergeNewTranslations();

        return $country;
    }
}
