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

use AppBundle\Entity\Company;
use AppBundle\Entity\Manager\UserManager;
use AppBundle\Entity\User;
use AppBundle\Model\UserInterface;
use AppBundle\Repository\UserRepository;
use Behat\Gherkin\Node\TableNode;

class UserContext extends BaseMinkContext
{
    /**
     * @Given /^there is a user:$/
     * @Given /^there are users:$/
     */
    public function thereAreEntities(TableNode $table)
    {
        foreach ($table->getHash() as $key => $values) {
            $entity = $this->buildEntity(
                $this->getValue($values, 'username', $values['email']),
                $values['email'],
                $this->getValue($values, 'password', 'secret'),
                $this->getBoolValue($values, 'enabled', isset($values['confirmationToken']) ? false : true),
                $this->getValue($values, 'role'),
                $this->getValue($values, 'confirmationToken'),
                $this->getValue($values, 'resetPasswordToken'),
                $this->hasValue($values, 'passwordResetAt') ? new \DateTime(
                    $this->getValue($values, 'passwordResetAt')
                ) : null,
                $this->getValue($values, 'managerFor')
            );

            $this->getUserRepository()->save($entity);
        }
    }

    /**
     * @Given /^there is an inactive user:$/
     */
    public function thereAreInactiveUsers(TableNode $table)
    {
        foreach ($table->getHash() as $key => $values) {
            $user = $this->buildEntity(
                $this->getValue($values, 'username', $values['email']),
                $values['email'],
                $values['password'],
                false,
                null,
                null,
                null,
                null
            );

            $this->getUserRepository()->save($user);
        }
    }

    /**
     * @Given /^there is an accountant:$/
     */
    public function thereIsAnAccountant(TableNode $table)
    {
        foreach ($table->getHash() as $key => $values) {
            $this->connectAccountant(
                $values['email'],
                $values['company']
            );
        }
    }

    /**
     * @Given /^there is a sales agent:$/
     */
    public function thereIsASalesAgent(TableNode $table)
    {
        foreach ($table->getHash() as $key => $values) {
            $this->connectSalesAgent(
                $values['email'],
                $values['company']
            );
        }
    }

    private function buildEntity(
        $username,
        $email,
        $password,
        $enabled,
        $role,
        $confirmationToken,
        $resetPasswordToken,
        \DateTime $passwordRequestedAt = null,
        $managerFor = null
    ) {
        $user = User::create($email, '', '');
        $user->setUsername($username);
        $user->setPlainPassword($password);
        $user->setEnabled($enabled);
        $user->setConfirmationToken($confirmationToken);
        $user->setResetPasswordToken($resetPasswordToken);
        $user->setPasswordRequestedAt($passwordRequestedAt);

        if ($managerFor) {
            /** @var Company $company */
            $company = $this->getCompanyRepository()->findOneByName($managerFor);
            $company->addManager($user);
        }

        /** @var UserManager $userManager */
        $userManager = $this->get(UserManager::class);

        /** @var UserInterface $user */
        $user = $userManager->updateUser($user);

        if ($role == 'superadmin') {
            $user->addRole(User::ROLE_SUPER_ADMIN);
            $user->addRole(User::ROLE_ALLOWED_TO_SWITCH);
            $user->addRole(User::ROLE_OWNER);
            $user->addRole(User::ROLE_CLERK);
        }
        if ($role == 'owner') {
            $user->addRole(User::ROLE_OWNER);
            $user->addRole(User::ROLE_CLERK);
        }
        if ($role == 'clerk') {
            $user->addRole(User::ROLE_CLERK);
        }
        if ($role == 'sales_agent') {
            $user->addRole(User::ROLE_SALES_AGENT);
        }
        if ($role == 'accountant') {
            $user->addRole(User::ROLE_OWNER);
            $user->addRole(User::ROLE_ACCOUNTANT);
        }

        return $user;
    }

    private function connectAccountant($email, $companyName)
    {
        /** @var User $user */
        $user = $this->getUserRepository()->findOneByEmail($email);

        /** @var Company $company */
        $company = $this->getCompanyRepository()->findOneByName($companyName);

        $company->setAccountant($user);
        $this->getCompanyRepository()->save($company);
    }

    private function connectSalesAgent($email, $companyName)
    {
        /** @var UserRepository $userRepo */
        $userRepo = $this->getUserRepository();

        /** @var User $user */
        $user = $userRepo->findOneByEmail($email);

        /** @var Company $company */
        $company = $this->getCompanyRepository()->findOneByName($companyName);

        $user->addMarketedCompany($company);
        $userRepo->save($company);
    }
}
