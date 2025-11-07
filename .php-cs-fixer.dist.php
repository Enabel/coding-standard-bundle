<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/src')
    ->in(__DIR__.'/tests')
;

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        'declare_strict_types' => ['preserve_existing_declaration' => true],
        'ordered_imports' => true,
        'no_unused_imports' => true,
        'header_comment' => [
            'header' => <<<EOF
This file is part of the EnabelCodingStandardBundle.
Copyright (c) Enabel <https://github.com/Enabel>
For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
EOF
        ]
    ])
    ->setFinder($finder)
;
