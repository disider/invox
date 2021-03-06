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

use App\Entity\City;
use Behat\Gherkin\Node\TableNode;

class CityContext extends AbstractMinkContext
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
