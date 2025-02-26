# This is my package filament-relation-manager-repeatable

[![Latest Version on Packagist](https://img.shields.io/packagist/v/zvizvi/filament-relation-manager-repeatable.svg?style=flat-square)](https://packagist.org/packages/zvizvi/filament-relation-manager-repeatable)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/zvizvi/filament-relation-manager-repeatable/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/zvizvi/filament-relation-manager-repeatable/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/zvizvi/filament-relation-manager-repeatable/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/zvizvi/filament-relation-manager-repeatable/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/zvizvi/filament-relation-manager-repeatable.svg?style=flat-square)](https://packagist.org/packages/zvizvi/filament-relation-manager-repeatable)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require zvizvi/filament-relation-manager-repeatable
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-relation-manager-repeatable-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-relation-manager-repeatable-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-relation-manager-repeatable-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$filamentRelationManagerRepeatable = new Zvizvi\FilamentRelationManagerRepeatable();
echo $filamentRelationManagerRepeatable->echoPhrase('Hello, Zvizvi!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [zvizvi](https://github.com/zvizvi)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
