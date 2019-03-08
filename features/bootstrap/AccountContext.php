<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

use App\Entity\Account;
use App\Entity\AccountType;
use Behat\Gherkin\Node\TableNode;

class AccountContext extends BaseMinkContext
{
    /**
     * @Given /^there is an account:$/
     * @Given /^there are accounts:$/
     */
    public function thereAreEntities(TableNode $table)
    {
        foreach ($table->getHash() as $key => $values) {
            $entity = $this->buildEntity(
                $values['company'],
                $this->getValue($values, 'type', AccountType::BANK),
                $values['name'],
                $this->getValue($values, 'initialAmount', 0.0),
                $this->getValue($values, 'currentAmount', 0.0),
                $this->getValue($values, 'iban', null)
            );

            $this->getAccountRepository()->save($entity);
        }
    }

    private function buildEntity($companyName, $type, $name, $initialAmount, $currentAmount, $iban)
    {
        $company = $this->getCompanyRepository()->findOneByName($companyName);

        return Account::create($company, $type, $name, $initialAmount, $currentAmount, $iban);
    }
}
