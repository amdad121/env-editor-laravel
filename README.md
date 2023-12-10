# ENV Editor for laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/amdad121/env-editor-laravel.svg?style=flat-square)](https://packagist.org/packages/amdad121/env-editor-laravel)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/amdad121/env-editor-laravel/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/amdad121/env-editor-laravel/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/amdad121/env-editor-laravel/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/amdad121/env-editor-laravel/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/amdad121/env-editor-laravel.svg?style=flat-square)](https://packagist.org/packages/amdad121/env-editor-laravel)

Simple ENV file editor for your Laravel.

## Installation

You can install the package via composer:

```bash
composer require amdad121/env-editor-laravel
```

## Usage

```php
use AmdadulHaq\EnvEditor\EnvEditor;

EnvEditor::set('APP_NAME', 'Laravel');

EnvEditor::update('APP_NAME', 'Laravel');

EnvEditor::setOrUpdate('APP_NAME', 'Laravel');

EnvEditor::remove('APP_NAME');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [Amdadul Haq](https://github.com/amdad121)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
