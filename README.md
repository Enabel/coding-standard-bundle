# Enabel: Coding Standard Bundle

[![License](https://img.shields.io/badge/license-MIT-red.svg?style=flat-square)](LICENSE)
[![SymfonyInsight](https://insight.symfony.com/projects/70165dd7-a479-4d2f-94d9-37025da4a11a/mini.svg)](https://insight.symfony.com/projects/70165dd7-a479-4d2f-94d9-37025da4a11a)

## Introduction

The bundle aims to provide basics Coding Standard and helper, including:

- PHP Code Sniffer (PSR-12)
- PHP Mess Detector
- PHPStan
- PHP Insight (Symfony)
- PHP Copy/Paste Detector
- Rector
- PHPLoc
- Twig Coding Standard
- Linter
  - Twig
  - Yaml
  - XLIFF
  - Symfony container
  - Symfony services
  - Composer
- Makefile
- Docker (MySQL, Redis, Mailcatcher, PHPQA)

## Installation & usage

You can check docs [here](docs/index.md)

## Versions & dependencies

The current version of the bundle works with Symfony 6.0+.
The project follows SemVer.

You can check the [changelog](CHANGELOG.md).

## Contributing

Feel free to contribute, like sending [pull requests](https://github.com/enabel/coding-standard-bundle/pulls) to add features/tests
or [creating issues](https://github.com/enabel/coding-standard-bundle/issues)

Note there are a few helpers to maintain code quality, that you can run using these commands:

```bash
composer cs # Code style check
composer stan # Static analysis
composer insight # Code analysis 
composer test # Run tests
```

