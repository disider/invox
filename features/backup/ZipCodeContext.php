<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

use App\Entity\ZipCode;
use Behat\Gherkin\Node\TableNode;

class ZipCodeContext extends BaseMinkContext
{
    /**
     * @Given /^there is a zip code:$/
     * @Given /^there are zip codes:$/
     */
    public function thereAreEntities(TableNode $table)
    {
        foreach ($table->getHash() as $key => $values) {
            $entity = $this->buildEntity(
                $values['city'],
                $values['code']
            );

            $this->getZipCodeRepository()->save($entity);
        }
    }

    private function buildEntity($cityName, $code)
    {
        $city = $this->getCityRepository()->findOneByName($cityName);

        return ZipCode::create($city, $code);
    }
}
