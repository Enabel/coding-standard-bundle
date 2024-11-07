<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Symfony\Set\SymfonySetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
    ]);
    $rectorConfig->symfonyContainerXml('/project/var/cache/dev/App_KernelDevDebugContainer.xml');

    $rectorConfig->import(SymfonySetList::SYMFONY_70);
    $rectorConfig->import(SymfonySetList::SYMFONY_CODE_QUALITY);
    $rectorConfig->import(SymfonySetList::ANNOTATIONS_TO_ATTRIBUTES);
    $rectorConfig->import(SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION);
    $rectorConfig->import(DoctrineSetList::DOCTRINE_CODE_QUALITY);
    $rectorConfig->import(DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES);
};
