<?php

return [
    // All
    A2lix\AutoFormBundle\A2lixAutoFormBundle::class => ['all' => true],
    A2lix\TranslationFormBundle\A2lixTranslationFormBundle::class => ['all' => true],
    Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle::class => ['all' => true],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => ['all' => true],
    Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class => ['all' => true],
    EWZ\Bundle\RecaptchaBundle\EWZRecaptchaBundle::class => ['all' => true],
    JMS\I18nRoutingBundle\JMSI18nRoutingBundle::class => ['all' => true],
    JMS\SerializerBundle\JMSSerializerBundle::class => ['all' => true],
    JMS\TranslationBundle\JMSTranslationBundle::class => ['all' => true],
    Knp\Bundle\MenuBundle\KnpMenuBundle::class => ['all' => true],
    Knp\Bundle\PaginatorBundle\KnpPaginatorBundle::class => ['all' => true],
    Knp\Bundle\SnappyBundle\KnpSnappyBundle::class => ['all' => true],
    Knp\DoctrineBehaviors\Bundle\DoctrineBehaviorsBundle::class => ['all' => true],
    Lexik\Bundle\FormFilterBundle\LexikFormFilterBundle::class => ['all' => true],
    Liip\ImagineBundle\LiipImagineBundle::class => ['all' => true],
    Nelmio\ApiDocBundle\NelmioApiDocBundle::class => ['all' => true],
    Oneup\UploaderBundle\OneupUploaderBundle::class => ['all' => true],
    Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle::class => ['all' => true],
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Symfony\Bundle\SecurityBundle\SecurityBundle::class => ['all' => true],
    Symfony\Bundle\TwigBundle\TwigBundle::class => ['all' => true],
    Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle::class => ['all' => true],
    Symfony\Bundle\MonologBundle\MonologBundle::class => ['all' => true],

    // Dev or test
    Symfony\Bundle\DebugBundle\DebugBundle::class => ['dev' => true, 'test' => true],
    Nelmio\Alice\Bridge\Symfony\NelmioAliceBundle::class => ['dev' => true, 'test' => true],
    Hautelook\AliceBundle\HautelookAliceBundle::class => ['dev' => true, 'test' => true],
    Fidry\AliceDataFixtures\Bridge\Symfony\FidryAliceDataFixturesBundle::class => ['dev' => true, 'test' => true],
    Symfony\Bundle\WebProfilerBundle\WebProfilerBundle::class => ['dev' => true, 'test' => true],
    Symfony\Bundle\WebServerBundle\WebServerBundle::class => ['dev' => true, 'test' => true],

    // Test only
    FriendsOfBehat\SymfonyExtension\Bundle\FriendsOfBehatSymfonyExtensionBundle::class => ['test' => true],
];
