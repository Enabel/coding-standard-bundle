<?php

declare(strict_types=1);

use NunoMaduro\PhpInsights\Domain\Insights\CyclomaticComplexityIsHigh;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenDefineFunctions;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenNormalClasses;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenTraits;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenGlobals;
use NunoMaduro\PhpInsights\Domain\Sniffs\ForbiddenSetterSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineLengthSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Formatting\SpaceAfterNotSniff;
use SlevomatCodingStandard\Sniffs\Classes\ForbiddenPublicPropertySniff;
use SlevomatCodingStandard\Sniffs\Classes\SuperfluousAbstractClassNamingSniff;
use SlevomatCodingStandard\Sniffs\Classes\SuperfluousErrorNamingSniff;
use SlevomatCodingStandard\Sniffs\Classes\SuperfluousExceptionNamingSniff;
use SlevomatCodingStandard\Sniffs\Classes\SuperfluousInterfaceNamingSniff;
use SlevomatCodingStandard\Sniffs\Classes\SuperfluousTraitNamingSniff;
use SlevomatCodingStandard\Sniffs\Commenting\DocCommentSpacingSniff;
use SlevomatCodingStandard\Sniffs\Commenting\InlineDocCommentDeclarationSniff;
use SlevomatCodingStandard\Sniffs\ControlStructures\DisallowEmptySniff;
use SlevomatCodingStandard\Sniffs\ControlStructures\DisallowShortTernaryOperatorSniff;
use SlevomatCodingStandard\Sniffs\ControlStructures\DisallowYodaComparisonSniff;
use SlevomatCodingStandard\Sniffs\Functions\FunctionLengthSniff;
use SlevomatCodingStandard\Sniffs\Functions\StaticClosureSniff;
use SlevomatCodingStandard\Sniffs\Functions\UnusedParameterSniff;
use SlevomatCodingStandard\Sniffs\Namespaces\UseSpacingSniff;
use SlevomatCodingStandard\Sniffs\Operators\DisallowEqualOperatorsSniff;
use SlevomatCodingStandard\Sniffs\Operators\RequireOnlyStandaloneIncrementAndDecrementOperatorsSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\DisallowMixedTypeHintSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\ParameterTypeHintSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\PropertyTypeHintSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\ReturnTypeHintSniff;

return [
    'preset' => 'symfony',
    'ide' => 'phpstorm',
    'exclude' => [
        'bin/.phpunit',
        'docker',
        'assets',
        'docs',
        'migrations',
        'node_modules',
        'src/Kernel.php',
        'tmp',
        'lib',
    ],
    'add' => [
    ],
    'remove' => [
        DisallowEmptySniff::class,
        DisallowShortTernaryOperatorSniff::class,
        DisallowYodaComparisonSniff::class,
        ForbiddenPublicPropertySniff::class,
        SuperfluousAbstractClassNamingSniff::class,
        SuperfluousErrorNamingSniff::class,
        SuperfluousExceptionNamingSniff::class,
        SuperfluousInterfaceNamingSniff::class,
        SuperfluousTraitNamingSniff::class,
        ForbiddenDefineFunctions::class,
        ForbiddenSetterSniff::class,
        ForbiddenTraits::class,
        ForbiddenGlobals::class,
        DocCommentSpacingSniff::class,
        InlineDocCommentDeclarationSniff::class,
        SpaceAfterNotSniff::class,
        ForbiddenNormalClasses::class,
        UnusedParameterSniff::class,
        StaticClosureSniff::class,
        DisallowMixedTypeHintSniff::class,
        DisallowEqualOperatorsSniff::class,
        ParameterTypeHintSniff::class,
        ReturnTypeHintSniff::class,
        RequireOnlyStandaloneIncrementAndDecrementOperatorsSniff::class,
    ],
    'config' => [
        LineLengthSniff::class => [
            'lineLimit' => 220,
            'absoluteLineLimit' => 250,
            'ignoreComments' => true,
        ],
        CyclomaticComplexityIsHigh::class => [
            'maxComplexity' => 60,
        ],
        FunctionLengthSniff::class => [
            'maxLinesLength' => 250,
        ],
        PropertyTypeHintSniff::class => [
            'enableNativeTypeHint' => false,
        ],
        UseSpacingSniff::class => [
            'linesCountBeforeFirstUse' => 1,
            'linesCountBetweenUseTypes' => 1,
            'linesCountAfterLastUse' => 1,
        ],
    ],
    'requirements' => [
        'min-quality' => 70,
        'min-complexity' => 70,
        'min-architecture' => 70,
        'min-style' => 70,
        'disable-security-check' => false,
    ],
    'threads' => null,
];
