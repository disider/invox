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

use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Driver\BrowserKitDriver;
use Behat\Mink\Session;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserProviderInterface;

trait ProfileContextTrait
{
    /**
     * @Given /^I am anonymous$/
     */
    public function iAmAnonymous()
    {
    }

    /**
     * @Given /^I am logged as "([^"]*)"$/
     */
    public function iAmLoggedAs($email)
    {
        $this->iAmLoggedAsWith($email, 'secret');
    }

    /**
     * @Given /^I am logged as "([^"]*)" and "([^"]*)"$/
     */
    public function iAmLoggedAsWith($email, $password)
    {
        /** @var Session $session */
        $session = $this->getSession();
        $driver = $session->getDriver();

        if ($driver instanceof BrowserKitDriver) {
            $client = $driver->getClient();

            $session = $client->getContainer()->get('session');

            /** @var UserProviderInterface $userProvider */
            $userProvider = $this->getUserProvider();
            $user = $userProvider->loadUserByUsername($email);

            $firewallName = $this->getFirewallName();

            $token = new UsernamePasswordToken($user, null, $firewallName, $user->getRoles());
            $session->set('_security_'.$firewallName, serialize($token));
            $session->save();

            $cookie = new Cookie($session->getName(), $session->getId());
            $client->getCookieJar()->set($cookie);
        } else {
            $this->visit('/login');
            $this->fillField('_username', $email);
            $this->fillField('_password', $password);
            $this->pressButton('login');
        }
    }

    /**
     * @When /^I enter my credentials:$/
     */
    public function iEnterMyCredentials(TableNode $table)
    {
        $hash = $table->getHash();
        $values = $hash[0];

        $this->visit('/login');
        $this->fillField('_username', $values['email']);
        $this->fillField('_password', $values['password']);
    }

    protected abstract function getUserProvider();

    protected abstract function getFirewallName();
}
