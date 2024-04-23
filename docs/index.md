# Installation

Make sure Composer is installed globally, as explained in the
[installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

## Applications that use Symfony Flex

### Step 0: Add our recipes endpoint

Add this in your composer.json:

```json
{
  "extra": {
    "symfony": {
      "endpoint": [
        "https://api.github.com/repos/Enabel/recipes/contents/index.json?ref=flex/main",
        "flex://defaults"
      ],
      "allow-contrib": true
    }
  }
}
```
**Don't forget to run `compose update` as you have just modified his configuration.**

### Step 1: Download the Bundle
Open a command console, enter your project directory and execute:

```bash
composer require enabel/coding-standard-bundle
```

### Step 2: Override files

Copy the docker & phpunit config to your project

```bash
cp -R vendor/enabel/coding-standard-bundle/assets/compose.* .
cp -R vendor/enabel/coding-standard-bundle/assets/phpunit.xml.dist .
```

## Applications that don't use Symfony Flex

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
composer require enabel/coding-standard-bundle
```

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    Enabel\CodingStandardBundle\EnabelCodingStandardBundle::class => ['all' => true],
];
```

### Step 3: Copy assets

Copy the content of the `assets` directory to your project directory

```bash
cp -pR vendor/enabel/coding-standard-bundle/assets/. .
```

### Step 4: Modify your .env 

Add this block in your .env file

```dotenv
###> enabel/coding-standard-bundle ###
MYSQL_VERSION=8
PHP_VERSION=8.2
DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=${MYSQL_VERSION}&charset=utf8mb4"
###< enabel/coding-standard-bundle ###
```
