# Changelog

All notable changes to `env-editor-laravel` will be documented in this file.

## Unreleased

### Added
- `get()` method to read individual .env values with default support
- `getAll()` method to retrieve all .env values as an array
- `has()` method to check if a key exists
- `backup()` method to create .env file backups with timestamp or custom names
- `restore()` method to restore from a backup file
- `listBackups()` method to list all available backups
- Custom .env file path support via constructor
- Dependency injection support for better testability

### Improved
- Better error handling with descriptive exception messages
- Improved value formatting to handle spaces, quotes, and special characters
- Better value parsing for quoted strings in .env files
- Refactored service provider to use standard Laravel ServiceProvider instead of spatie/laravel-package-tools
- Removed dependency on spatie/laravel-package-tools
- Full test coverage with comprehensive test cases

### Changed
- Changed dependency from `illuminate/contracts` to `illuminate/support`
- Improved internal architecture with better separation of concerns

## v1.1.0 - 2024-10-12

**Full Changelog**: https://github.com/amdad121/env-editor-laravel/compare/v1.0.1...v1.1.0

## v1.0.1 - 2024-10-08

**Full Changelog**: https://github.com/amdad121/env-editor-laravel/compare/v1.0.0...v1.0.1

## v1.0.0 - 2024-10-08

**Full Changelog**: https://github.com/amdad121/env-editor-laravel/commits/v1.0.0
