<?php

use PSS\SymfonyMockerContainer\DependencyInjection\MockerContainer;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            new A2lix\TranslationFormBundle\A2lixTranslationFormBundle(),
            new Braincrafted\Bundle\BootstrapBundle\BraincraftedBootstrapBundle(),
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new Genemu\Bundle\FormBundle\GenemuFormBundle(),
            new JMS\I18nRoutingBundle\JMSI18nRoutingBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new JMS\TranslationBundle\JMSTranslationBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new Knp\Bundle\SnappyBundle\KnpSnappyBundle(),
            new Knp\DoctrineBehaviors\Bundle\DoctrineBehaviorsBundle(),
            new Lexik\Bundle\FormFilterBundle\LexikFormFilterBundle(),
            new Liip\ImagineBundle\LiipImagineBundle(),
            new Nelmio\ApiDocBundle\NelmioApiDocBundle(),
            new Oneup\UploaderBundle\OneupUploaderBundle(),
            new Webfactory\Bundle\ExceptionsBundle\WebfactoryExceptionsBundle(),

            new Sonata\CoreBundle\SonataCoreBundle(),
            new Sonata\BlockBundle\SonataBlockBundle(),

            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
            new Sonata\AdminBundle\SonataAdminBundle(),

            new AdminBundle\AdminBundle(),
            new AppBundle\AppBundle(),
        );

        if (in_array($this->getEnvironment(), ['stage', 'dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
            $bundles[] = new Hautelook\AliceBundle\HautelookAliceBundle();
            $bundles[] = new Diside\GeneratorBundle\DisideGeneratorBundle();
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        $envDir = getenv('SYMFONY_CACHE_DIR');
        if ($envDir && in_array($this->environment, array('dev', 'test'))) {
            return $envDir . DIRECTORY_SEPARATOR . $this->environment;
        }

        return dirname(__DIR__) . '/var/cache/' . $this->getEnvironment();
    }

    public function getLogDir()
    {
        $envDir = getenv('SYMFONY_LOGS_DIR');
        if ($envDir && in_array($this->environment, array('dev', 'test'))) {
            return $envDir . DIRECTORY_SEPARATOR . $this->environment;
        }

        return dirname(__DIR__) . '/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir() . '/config/config_' . $this->getEnvironment() . '.yml');
    }

    /**
     * @return string
     */
    protected function getContainerBaseClass()
    {
        if (in_array($this->environment, ['test', 'dev'])) {
            return MockerContainer::class;
        }

        return parent::getContainerBaseClass();
    }
}
