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

use AppBundle\Entity\Medium;
use Behat\Gherkin\Node\TableNode;

class MediumContext extends BaseMinkContext
{

    /**
     * @Given /^there is a medium:$/
     * @Given /^there are media:$/
     */
    public function thereAreMedia(TableNode $table)
    {
        $repository = $this->getMediumRepository();

        foreach ($table->getHash() as $values) {
            $entity = $this->buildEntity(
                $values['fileName'],
                $values['fileUrl'],
                $values['company']
            );
            $repository->save($entity);
        }
    }

    private function buildEntity($fileName, $fileUrl, $companyName)
    {
        $company = $this->getCompanyRepository()->findOneByName($companyName);

        return Medium::create($fileName, $fileUrl, $company);
    }
}
