# ENV Editor for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/amdadulhaq/env-editor-laravel.svg?style=flat-square)](https://packagist.org/packages/amdadulhaq/env-editor-laravel)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/amdad121/env-editor-laravel/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/amdad121/env-editor-laravel/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/amdad121/env-editor-laravel/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/amdad121/env-editor-laravel/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/amdadulhaq/env-editor-laravel.svg?style=flat-square)](https://packagist.org/packages/amdadulhaq/env-editor-laravel)

Simple and powerful ENV file editor for your Laravel application.

## Installation

You can install the package via composer:

```bash
composer require amdadulhaq/env-editor-laravel
```

## Usage

### Basic Methods

```php
use AmdadulHaq\EnvEditor\EnvEditor;

// Set a new key or update an existing one
EnvEditor::set('APP_NAME', 'MyApp');

// Update an existing key (throws exception if key doesn't exist)
EnvEditor::update('APP_NAME', 'MyApp');

// Set or update (smart - checks if key exists)
EnvEditor::setOrUpdate('APP_NAME', 'MyApp');

// Remove a key
EnvEditor::remove('APP_NAME');
```

### Reading Values

```php
// Get a single value
$appName = EnvEditor::get('APP_NAME');
$appName = EnvEditor::get('NON_EXISTENT_KEY', 'default_value'); // with default

// Get all values as an array
$allEnv = EnvEditor::getAll();

// Check if a key exists
$hasKey = EnvEditor::has('APP_NAME');
```

### Backup and Restore

```php
// Create a backup (auto-generates timestamp-based filename)
$backupPath = EnvEditor::backup();

// Create a backup with custom name
$backupPath = EnvEditor::backup('backup-before-deployment.env');

// List all available backups
$backups = EnvEditor::listBackups();

// Restore from a backup
EnvEditor::restore('backup-before-deployment.env');
```

### Using with Dependency Injection

```php
use AmdadulHaq\EnvEditor\EnvEditor;

class SomeService
{
    public function __construct(
        protected EnvEditor $envEditor
    ) {}

    public function updateSettings()
    {
        $this->envEditor->set('SOME_KEY', 'value');
        $value = $this->envEditor->get('SOME_KEY');
    }
}
```

### Custom ENV File Path

If you need to work with a different .env file:

```php
$editor = new EnvEditor('/path/to/custom/.env');
$editor->set('KEY', 'value');
```

## Features

- ✅ Read, write, and update .env file values
- ✅ Check if keys exist
- ✅ Get all environment variables as an array
- ✅ Backup and restore .env files
- ✅ Handle values with spaces, quotes, and special characters
- ✅ Dependency injection support
- ✅ Custom .env file path support
- ✅ Comprehensive error handling
- ✅ Full test coverage with Pest

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

- [Amdadul Haq](https://github.com/amdad121)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
