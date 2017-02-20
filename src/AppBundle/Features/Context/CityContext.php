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

use AppBundle\Entity\City;
use Behat\Gherkin\Node\TableNode;

class CityContext extends BaseMinkContext
{
    /**
     * @Given /^there is a city:$/
     * @Given /^there are cities:$/
     */
    public function thereAreEntities(TableNode $table)
    {
        foreach ($table->getHash() as $key => $values) {
            $entity = $this->buildEntity(
                $values['province'],
                $values['name']
            );

            $this->getCityRepository()->save($entity);
        }
    }

    private function buildEntity($provinceName, $name)
    {
        $province = $this->getProvinceRepository()->findOneByName($provinceName);

        return City::create($province, $name);
    }
}
