# Enabel: Coding Standard Bundle

[![License](https://img.shields.io/badge/license-MIT-red.svg?style=flat-square)](LICENSE)
[![SymfonyInsight](https://insight.symfony.com/projects/70165dd7-a479-4d2f-94d9-37025da4a11a/mini.svg)](https://insight.symfony.com/projects/70165dd7-a479-4d2f-94d9-37025da4a11a)
[![codecov](https://codecov.io/gh/Enabel/coding-standard-bundle/graph/badge.svg?token=RUPxinbfup)](https://codecov.io/gh/Enabel/coding-standard-bundle)
[![CI](https://github.com/Enabel/coding-standard-bundle/actions/workflows/CI.yml/badge.svg)](https://github.com/Enabel/coding-standard-bundle/actions/workflows/CI.yml)

## Introduction

The bundle aims to provide basics Coding Standard and helper, including:

- PHPCsFixer (PSR-12, Symfony)
- PHPStan (Symfony, Doctrine, PHPUnit)
- Rector (Symfony, Doctrine, PHPUnit, Twig)
- Linter
  - Twig
  - Yaml
  - Symfony container
  - Symfony services
  - Doctrine
  - Composer
- Makefile
- Docker (MySQL, Redis, Mailcatcher)

## Installation & usage

You can check docs [here](docs/index.md)

## Versions & dependencies

The current version of the bundle works with Symfony 7.0+.
The project follows SemVer.

You can check the [changelog](CHANGELOG.md).

## Contributing

Feel free to contribute, like sending [pull requests](https://github.com/enabel/coding-standard-bundle/pulls) to add features/tests
or [creating issues](https://github.com/enabel/coding-standard-bundle/issues)

Note there are a few helpers to maintain code quality, that you can run using these commands:

```bash
composer csf # Code style check
composer stan # Static analysis
composer test # Run tests
```

