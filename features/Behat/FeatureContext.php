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

use App\Entity\Company;
use App\Helper\ParameterHelperInterface;
use App\Mailer\MailerInterface;
use Behat\Gherkin\Node\TableNode;
use Diside\BehatExtension\Context\AbstractContext;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Assert as a;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Tests\App\Fake\FakeMailer;
use Tests\App\Fake\FakeParameterHelper;

class FeatureContext extends AbstractContext
{
    use EntityLookupContextTrait;
    use ProfileContextTrait;

    /** @var ContainerInterface */
    private $container;

    /** @var FakeMailer */
    private $mailer;

    /** @var FakeParameterHelper */
    private $parameterHelper;

    /** @var EntityManagerInterface */
    private $entityManager;
    private $userProvider;
    private $session;

    public function __construct(
        ContainerInterface $container,
        ParameterHelperInterface $parameterHelper,
        MailerInterface $mailer,
        EntityManagerInterface $entityManager,
        UserProviderInterface $userProvider,
        SessionInterface $session
    ) {
        $this->setFilePath(__DIR__.'/attachments');

        $this->container = $container;
        $this->parameterHelper = $parameterHelper;
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
        $this->userProvider = $userProvider;
        $this->session = $session;
    }

    /** @BeforeScenario */
    public function setUpServices()
    {
        $this->parameterHelper->setDemoMode(false);
    }

    /** @AfterScenario */
    public function tearDownServices()
    {
        $this->mailer->clearEmails();
    }

    /** @BeforeScenario */
    public function purgeDatabase()
    {
        $purger = new ORMPurger($this->entityManager);
        $purger->purge();
    }

    /** @BeforeScenario */
    public function clearOrphanages()
    {
        $fs = new Filesystem();
        $fs->remove($this->getOrphanageDir());
    }

    public function getUserProvider()
    {
        return $this->userProvider;
    }

    public function getFirewallName()
    {
        return 'main';
    }

    /**
     * @Given /^an? "([^"]*)" email should be sent to "([^"]*)"$/
     */
    public function anEmailIsSentTo($subject, $recipient)
    {
        a::assertTrue($this->mailer->hasSubject($subject), sprintf('No "%s" email found', $subject));

        $email = $this->mailer->getSubject($subject);
        a::assertTrue(
            $email->hasRecipient($recipient),
            sprintf('No "%s" recipient found for "%s"', $recipient, $subject)
        );
    }

    /**
     * @Given /^an? "([^"]*)" email should be sent from "([^"]*)"$/
     */
    public function anEmailIsSentFrom($subject, $sender)
    {
        a::assertTrue($this->mailer->hasSubject($subject), sprintf('No "%s" email found', $subject));

        $email = $this->mailer->getSubject($subject);
        a::assertThat($email->sender, a::equalTo($sender), sprintf('No "%s" sender found for "%s"', $sender, $subject));
    }

    /**
     * @Given /^no "([^"]*)" email should be sent to "([^"]*)"$/
     */
    public function noEmailIsSentTo($type, $address)
    {
        a::assertFalse($this->mailer->hasSubject($type));
    }

    /**
     * @Given /^I should see the "([^"]*)" link: (yes|y|no|n)$/
     */
    public function iShouldSeeLink($link, $visible)
    {
        $link = $this->replacePlaceholders($link);
        $visible = strtoupper($visible);

        if ($visible == 'YES' || $visible == 'Y') {
            $this->assertSession()->elementExists('xpath', $this->formatXpathLink($link));
        } else {
            $this->assertSession()->elementNotExists('xpath', $this->formatXpathLink($link));
        }
    }

    /**
     * @Given /^I should see the "([^"]*)" translations for "([^"]*)" form errors:$/
     */
    public function iShouldSeeTheTranslationsForFormErrors($locale, $form, TableNode $table)
    {
        $form = $this->replacePlaceholders($form);

        foreach ($table->getRowsHash() as $field => $value) {
            $field = str_replace('.', '_', $field);

            $element = sprintf('div.has-error label[for="%s_translations_%s_%s"] ~ div > ul', $form, $locale, $field);
            $this->assertElementContains($element, $value);
        }
    }

    public function iShouldSeeTheFormErrors($form, TableNode $table)
    {
        $form = $this->replacePlaceholders($form);

        foreach ($table->getRowsHash() as $field => $value) {
            $field = str_replace('.', '_', $field);

            $element = sprintf('div.has-error label[for="%s_%s"] ~ div > ul', $form, $field);
            $this->assertElementContains($element, $value);
        }
    }

    /**
     * @Given /^there is a file "([^"]*)" into the "([^"]*)" orphanage$/
     */
    public function thereIsAFileIntoTheOrphanage($fileName, $type)
    {
        $session = $this->get('session');

        /** @var Company $company */
        $company = $this->getCompanyRepository()->findLast();

        $orphanagePath = $this->getOrphanageDir();
        $path = sprintf('%s/%s/%s/%s', $orphanagePath, $session->getId(), $type, $company->getAttachmentsUploadDir());

        $this->saveFile($fileName, $path.'/'.$fileName);
    }

    /**
     * @When /^I drop "([^"]*)" in the "([^"]*)" field$/
     */
    public function iDropInTheField($fileName, $container)
    {
        $script = sprintf(
            '$("#%s").fineUploader("addFiles", { name: "%s", blob: new Blob(["%s"]) });',
            $container,
            $fileName,
            'abc'
        );

        $this->executeScript($script);
    }

    protected function getOrphanageDir()
    {
        return $this->getConfigParameter('kernel.cache_dir').'/uploader/orphanage';
    }

    protected function getConfigParameter($param)
    {
        return $this->container->getParameter($param);
    }

    /**
     * @Given /^a file should be saved into "([^"]*)"$/
     */
    public function aFileIsSavedInto($fileName)
    {
        $fileName = $this->replacePlaceholders($fileName);

        $root = __DIR__.'/../../../../web';

        if (!is_file($root.'/'.$fileName)) {
            throw new \InvalidArgumentException(sprintf('File %s not found inside %s', $fileName, $root));
        }
    }

    /**
     * @Given /^no file should be saved into "([^"]*)"$/
     */
    public function noFileIsSavedInto($fileName)
    {
        $fileName = $this->replacePlaceholders($fileName);

        $root = __DIR__.'/../../../../web';

        if (is_file($root.'/'.$fileName)) {
            throw new \InvalidArgumentException(
                sprintf('File %s found inside %s, but it should not exist', $fileName, $root)
            );
        }
    }

    /**
     * @Then /^save last response$/
     */
    public function saveLastResponse()
    {
        $content = $this->getSession()->getPage()->getContent();
        file_put_contents('response.html', $content);
    }

    /**
     * @Given /^I wait until the upload completes$/
     */
    public function iWaitUntilTheUploadCompletes()
    {
        $this->getSession()->wait(5000, '$(".qq-in-progress").length == 0');
    }

    /**
     * @Given /^I wait until the typeahead completes$/
     */
    public function iWaitUntilTheTypeaheadCompletes()
    {
        $this->getSession()->wait(
            10000,
            '($(".tt-highlight").length !== 0) || ($(".tt-dataset .empty-list").length !== 0)'
        );
    }

    public function saveFile($source, $destination)
    {
        $filePath = $this->filePath.'/'.$source;
        if (!is_file($filePath)) {
            throw new InvalidArgumentException(sprintf('File not found in %s', $filePath));
        }

        $fs = new Filesystem();
        $fs->copy($filePath, $destination);
    }

    /**
     * @Given /^I trigger a "([^"]*)" event on "([^"]*)"$/
     */
    public function iTriggerAEventOn($event, $selector)
    {
        $session = $this->getSession();

        $script = sprintf('$("%s").trigger("%s");;', $selector, $event);
        $session->executeScript($script);
    }

    /**
     * @Given /^I fill the "([^"]*)" field and select the "([^"]*)" option$/
     */
    public function iFillAndSelectTheFieldWith($field, $text)
    {
        $this->iFillTheFieldWith($field, $text);

        $this->iWaitUntilTheTypeaheadCompletes();
        $this->iTriggerAEventOn('click', '.tt-selectable:nth-child(1)');
    }

    /**
     * @param $script
     */
    protected function executeScript($script)
    {
        $session = $this->getSession();
        $session->executeScript($script);
    }

    /**
     * @Given /^I fill the "([^"]*)" field with "([^"]*)" and wait until the typeahead completes$/
     */
    public function iFillTheFieldAndWait($field, $text)
    {
        $this->iFillTheFieldWith($field, $text);

        $this->iWaitUntilTheTypeaheadCompletes();

        $this->executeScript('$(".tt-menu").show()');
    }

    /**
     * @Given /^I wait to see "([^"]*)"$/
     */
    public function iWaitToSee($element)
    {
        $this->getSession()->wait(10000, sprintf("$('%s').is(':visible');", $element));
    }

    /**
     * @Given /^I wait to see (\d+) "([^"]*)"(s|es)?$/
     */
    public function iWaitToSeeParagraphS($num, $element)
    {
        $this->getSession()->wait(10000, sprintf("$('.%s').length == %d;", $element, $num));
    }

    /**
     * @Given /^I wait to load data$/
     */
    public function iWaitToLoadData()
    {
        $this->getSession()->wait(1000);
    }

    /**
     * @Given /^I should see no "([^"]*)" element/
     */
    public function iShouldSeeNoElement($class)
    {
        $nodeElement = $this->getMink()->getSession()->getPage()->find('css', '.'.$class);
        a::assertNull($nodeElement);
    }

    /**
     * @Given /^the demo mode is enabled/
     */
    public function enableDemoMode()
    {
        $this->parameterHelper->setDemoMode(true);
    }

    /**
     * @Then /^die$/
     */
    public function die()
    {
        die();
    }
}
