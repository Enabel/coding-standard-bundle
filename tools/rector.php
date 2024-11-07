<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector;
use Rector\Symfony\Bridge\Symfony\Routing\SymfonyRoutesProvider;
use Rector\Symfony\Contract\Bridge\Symfony\Routing\SymfonyRoutesProviderInterface;
use Rector\Symfony\Set\SymfonySetList;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        typeDeclarations: true,
        privatization: true,
        instanceOf: true,
        earlyReturn: true,
        strictBooleans: true,
        rectorPreset: true,
        doctrineCodeQuality: true,
        symfonyCodeQuality: true,
        twig: true
    )
    ->withPhpSets(php83: true)
    ->withSets([
        SymfonySetList::SYMFONY_71,
        SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,
        DoctrineSetList::DOCTRINE_BUNDLE_210,
        DoctrineSetList::DOCTRINE_DBAL_40,
        DoctrineSetList::GEDMO_ANNOTATIONS_TO_ATTRIBUTES,
    ])
    ->withSymfonyContainerXml(__DIR__ . '/var/cache/dev/App_KernelDevDebugContainer.xml')
    ->registerService(SymfonyRoutesProvider::class, SymfonyRoutesProviderInterface::class)
    ->withPhpVersion(PhpVersion::PHP_83)
    ->withAttributesSets(
        symfony: true,
        doctrine: true,
        gedmo: true,
        phpunit: true
    )
    ->withSkip([
        AddOverrideAttributeToOverriddenMethodsRector::class,
    ])
    ->withPaths([
        __DIR__ . '/src',
    ]);
