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

use App\Entity\Attachable;
use App\Entity\AttachmentContainer;
use App\Entity\Country;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\Environment\InitializedContextEnvironment;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Diside\BehatExtension\Context\ContextTrait;

abstract class AbstractMinkContext implements Context, KernelAwareContext
{
    use ContextTrait;
    use EntityLookupContextTrait;

    /** @var FeatureContext */
    private $mainContext;

    /** @BeforeScenario */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        /** @var InitializedContextEnvironment $environment */
        $environment = $scope->getEnvironment();

        $this->mainContext = $environment->getContext(FeatureContext::class);
    }

    public function getEntityLookupTables()
    {
        return $this->mainContext->getEntityLookupTables();
    }

    public function getExpressionLanguage()
    {
        return $this->mainContext->getExpressionLanguage();
    }

    protected function getCountry($countryCode)
    {
        $countryRepository = $this->getCountryRepository();

        if ($countryCode) {
            $country = $countryRepository->findOneByCode($countryCode);
        } else {
            $country = $countryRepository->findLast();
        }

        if (!$country) {
            $country = Country::create('IT');
            $country->mergeNewTranslations();

            $countryRepository->save($country);
        }

        return $country;
    }

    protected function buildAttachment(AttachmentContainer $container, $fileName, $fileUrl, $class)
    {
        /** @var Attachable $attachment */
        $attachment = new $class();
        $attachment->setFileName($fileName);
        $attachment->setFileUrl($fileUrl);

        $this->mainContext->saveFile($fileName, $container->getAttachmentsUploadRootDir().'/'.$fileUrl);

        $container->addAttachment($attachment);

        return $attachment;
    }

    protected function getMainContext()
    {
        return $this->mainContext;
    }

}
