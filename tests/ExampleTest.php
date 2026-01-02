<?php

declare(strict_types=1);

use AmdadulHaq\EnvEditor\EnvEditor;

beforeEach(function (): void {
    $this->envFile = tempnam(sys_get_temp_dir(), 'env_test_');
    file_put_contents($this->envFile, "APP_NAME=Laravel\nAPP_ENV=local\nAPP_DEBUG=true\n");

    $this->editor = new EnvEditor($this->envFile);
});

afterEach(function (): void {
    if (file_exists($this->envFile)) {
        unlink($this->envFile);
    }

    $backupDir = dirname($this->envFile).'/.env.backup';
    if (is_dir($backupDir)) {
        array_map(unlink(...), glob($backupDir.'/*'));
        rmdir($backupDir);
    }
});

it('can get a value from env file', function (): void {
    expect($this->editor->get('APP_NAME'))->toBe('Laravel');
    expect($this->editor->get('APP_ENV'))->toBe('local');
    expect($this->editor->get('APP_DEBUG'))->toBe('true');
});

it('returns default value when key does not exist', function (): void {
    expect($this->editor->get('NON_EXISTENT_KEY', 'default'))->toBe('default');
});

it('can get all env values', function (): void {
    $all = $this->editor->getAll();

    expect($all)->toBeArray()
        ->and($all['APP_NAME'])->toBe('Laravel')
        ->and($all['APP_ENV'])->toBe('local')
        ->and($all['APP_DEBUG'])->toBe('true');
});

it('can check if key exists', function (): void {
    expect($this->editor->has('APP_NAME'))->toBeTrue()
        ->and($this->editor->has('NON_EXISTENT'))->toBeFalse();
});

it('can update an existing key', function (): void {
    $result = $this->editor->update('APP_NAME', 'MyApp');

    expect($result)->toBeTrue()
        ->and($this->editor->get('APP_NAME'))->toBe('MyApp');
});

it('throws exception when updating non-existent key', function (): void {
    $this->editor->update('NON_EXISTENT', 'value');
})->throws(Exception::class, "Key 'NON_EXISTENT' does not exist in .env file");

it('can set a new key', function (): void {
    $result = $this->editor->set('NEW_KEY', 'new_value');

    expect($result)->toBeTrue()
        ->and($this->editor->get('NEW_KEY'))->toBe('new_value');
});

it('can set or update a key', function (): void {
    $result1 = $this->editor->setOrUpdate('APP_NAME', 'UpdatedApp');
    expect($result1)->toBeTrue()
        ->and($this->editor->get('APP_NAME'))->toBe('UpdatedApp');

    $result2 = $this->editor->setOrUpdate('NEW_KEY', 'new_value');
    expect($result2)->toBeTrue()
        ->and($this->editor->get('NEW_KEY'))->toBe('new_value');
});

it('can remove a key', function (): void {
    $result = $this->editor->remove('APP_DEBUG');

    expect($result)->toBeTrue()
        ->and($this->editor->has('APP_DEBUG'))->toBeFalse();
});

it('can handle values with spaces', function (): void {
    $this->editor->set('APP_URL', 'https://example.com/path with spaces');

    expect($this->editor->get('APP_URL'))->toBe('https://example.com/path with spaces');
});

it('can handle values with quotes', function (): void {
    $this->editor->set('APP_NAME', 'My "App" Name');

    expect($this->editor->get('APP_NAME'))->toBe('My "App" Name');
});

it('can handle empty values', function (): void {
    $this->editor->set('EMPTY_KEY', '');

    expect($this->editor->get('EMPTY_KEY'))->toBe('');
});

it('can backup the env file', function (): void {
    $backupPath = $this->editor->backup();

    expect($backupPath)->toBeString()
        ->and(file_exists($backupPath))->toBeTrue();

    $backups = $this->editor->listBackups();
    expect($backups)->toHaveCount(1);
});

it('can backup with custom name', function (): void {
    $backupPath = $this->editor->backup('my-backup.env');

    expect($backupPath)->toContain('my-backup.env')
        ->and(file_exists($backupPath))->toBeTrue();
});

it('can list backups', function (): void {
    $this->editor->backup('backup1.env');
    $this->editor->backup('backup2.env');

    $backups = $this->editor->listBackups();

    expect($backups)->toHaveCount(2)
        ->and($backups)->toContain('backup1.env')
        ->and($backups)->toContain('backup2.env');
});

it('can restore from backup', function (): void {
    $this->editor->backup('my-backup.env');

    $this->editor->remove('APP_NAME');

    expect($this->editor->has('APP_NAME'))->toBeFalse();

    $this->editor->restore('my-backup.env');
    expect($this->editor->get('APP_NAME'))->toBe('Laravel');
});

it('throws exception when restoring non-existent backup', function (): void {
    $this->editor->restore('non-existent.env');
})->throws(Exception::class, "Backup file 'non-existent.env' does not exist");

it('handles empty env file', function (): void {
    file_put_contents($this->envFile, '');
    $editor = new EnvEditor($this->envFile);

    expect($editor->getAll())->toBeEmpty()
        ->and($editor->get('NON_EXISTENT', 'default'))->toBe('default');
});

it('handles env file with comments', function (): void {
    file_put_contents($this->envFile, "# This is a comment\nAPP_NAME=Laravel\n# Another comment\nAPP_ENV=local\n");
    $editor = new EnvEditor($this->envFile);

    $all = $editor->getAll();
    expect($all)->toHaveCount(2)
        ->and($all['APP_NAME'])->toBe('Laravel')
        ->and($all['APP_ENV'])->toBe('local');
});

it('can set numeric values', function (): void {
    $this->editor->set('PORT', 8080);
    $this->editor->set('PERCENTAGE', 99.5);

    expect($this->editor->get('PORT'))->toBe('8080')
        ->and($this->editor->get('PERCENTAGE'))->toBe('99.5');
});

it('preserves other keys when updating', function (): void {
    $this->editor->update('APP_NAME', 'UpdatedApp');

    expect($this->editor->get('APP_ENV'))->toBe('local')
        ->and($this->editor->get('APP_DEBUG'))->toBe('true');
});
