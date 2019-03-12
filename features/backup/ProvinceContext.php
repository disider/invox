<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

use App\Entity\Province;
use Behat\Gherkin\Node\TableNode;

class ProvinceContext extends BaseMinkContext
{
    /**
     * @Given /^there is a province:$/
     * @Given /^there are provinces:$/
     */
    public function thereAreEntities(TableNode $table)
    {
        foreach ($table->getHash() as $key => $values) {
            $entity = $this->buildEntity(
                $values['country'],
                $values['name'],
                $this->getValue($values, 'code', substr($values['name'], 0, 5))
            );

            $this->getProvinceRepository()->save($entity);
        }
    }

    private function buildEntity($countryCode, $name, $code)
    {
        $country = $this->getCountryRepository()->findOneByCode($countryCode);

        return Province::create($country, $name, $code);
    }
}
