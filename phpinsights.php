<?php

declare(strict_types=1);

use NunoMaduro\PhpInsights\Domain\Insights\CyclomaticComplexityIsHigh;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenGlobals;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenNormalClasses;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenTraits;
use NunoMaduro\PhpInsights\Domain\Sniffs\ForbiddenSetterSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineLengthSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Formatting\SpaceAfterNotSniff;
use SlevomatCodingStandard\Sniffs\Classes\SuperfluousAbstractClassNamingSniff;
use SlevomatCodingStandard\Sniffs\Classes\SuperfluousInterfaceNamingSniff;
use SlevomatCodingStandard\Sniffs\Classes\SuperfluousTraitNamingSniff;
use SlevomatCodingStandard\Sniffs\Commenting\DocCommentSpacingSniff;
use SlevomatCodingStandard\Sniffs\ControlStructures\DisallowEmptySniff;
use SlevomatCodingStandard\Sniffs\ControlStructures\DisallowYodaComparisonSniff;
use SlevomatCodingStandard\Sniffs\Functions\FunctionLengthSniff;
use SlevomatCodingStandard\Sniffs\Functions\StaticClosureSniff;
use SlevomatCodingStandard\Sniffs\Functions\UnusedParameterSniff;
use SlevomatCodingStandard\Sniffs\Namespaces\UseSpacingSniff;
use SlevomatCodingStandard\Sniffs\Operators\DisallowEqualOperatorsSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\DisallowMixedTypeHintSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\ParameterTypeHintSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\PropertyTypeHintSniff;

return [
    'preset' => 'symfony',
    'ide' => 'phpstorm',
    'exclude' => [
        'assets',
        'docs',
        'phpinsights.php',
    ],
    'remove' => [
        DisallowEmptySniff::class,
        DisallowYodaComparisonSniff::class,
        SuperfluousAbstractClassNamingSniff::class,
        SuperfluousInterfaceNamingSniff::class,
        SuperfluousTraitNamingSniff::class,
        ForbiddenDefineFunctions::class,
        ForbiddenSetterSniff::class,
        ForbiddenTraits::class,
        DocCommentSpacingSniff::class,
        SpaceAfterNotSniff::class,
        ForbiddenNormalClasses::class,
        ForbiddenGlobals::class,
        UnusedParameterSniff::class,
        StaticClosureSniff::class,
        DisallowMixedTypeHintSniff::class,
        DisallowEqualOperatorsSniff::class,
        ParameterTypeHintSniff::class,
    ],
    'config' => [
        LineLengthSniff::class => [
            'lineLimit' => 120,
            'absoluteLineLimit' => 150,
            'ignoreComments' => true,
        ],
        CyclomaticComplexityIsHigh::class => [
            'maxComplexity' => 60,
        ],
        FunctionLengthSniff::class => [
            'maxLinesLength' => 200,
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
        'min-quality' => 90,
        'min-complexity' => 70,
        'min-architecture' => 90,
        'min-style' => 90,
        'disable-security-check' => false,
    ],
    'threads' => null,
];
