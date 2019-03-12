<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

use App\Entity\Invite;
use App\Generator\TokenGenerator;
use Behat\Gherkin\Node\TableNode;

class InviteContext extends BaseMinkContext
{
    /**
     * @Given /^there is an accountant invite:$/
     */
    public function thereAreEntities(TableNode $table)
    {
        foreach ($table->getHash() as $key => $values) {
            $entity = $this->buildEntity(
                $values['email'],
                $values['company'],
                $this->getValue($values, 'token', TokenGenerator::generateToken())
            );

            $this->getInviteRepository()->save($entity);
        }
    }

    private function buildEntity($accountantEmail, $companyName, $token)
    {
        $company = $this->getCompanyRepository()->findOneByName($companyName);

        $invite = Invite::create($company, $company->getOwner(), $token);
        $invite->setEmail($accountantEmail);

        return $invite;
    }
}
