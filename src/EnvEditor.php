<?php

declare(strict_types=1);

namespace AmdadulHaq\EnvEditor;

use Exception;

class EnvEditor
{
    protected string $envFile;

    protected string $backupDir;

    public function __construct(?string $envFile = null)
    {
        $this->envFile = $envFile ?? (function_exists('base_path') ? base_path('.env') : '.env');
        $this->backupDir = dirname($this->envFile).'/.env.backup';
    }

    public function set(string $key, $value): bool
    {
        $formattedValue = $this->formatValue($value);

        if ($this->keyExists($key)) {
            return $this->update($key, $value);
        }

        return $this->append($key, $formattedValue);
    }

    public function update(string $key, $value): bool
    {
        throw_unless($this->keyExists($key), Exception::class, sprintf("Key '%s' does not exist in .env file", $key));

        $formattedValue = $this->formatValue($value);
        $envContent = $this->readFile();

        $envContent = preg_replace(sprintf('/^%s=.*$/m', $this->escapeKey($key)), sprintf('%s=%s', $key, $formattedValue), $envContent);

        return $this->writeFile($envContent);
    }

    public function setOrUpdate(string $key, $value): bool
    {
        if ($this->keyExists($key)) {
            return $this->update($key, $value);
        }

        return $this->set($key, $value);
    }

    public function remove(string $key): bool
    {
        if (! $this->keyExists($key)) {
            return true;
        }

        $envContent = $this->readFile();

        $pattern = sprintf('/^%s=.*$/m', $this->escapeKey($key));
        $envContent = preg_replace($pattern, '', $envContent);
        $envContent = preg_replace("/^\s*\n/m", '', (string) $envContent);

        return $this->writeFile(trim((string) $envContent).PHP_EOL);
    }

    public function get(string $key, $default = null)
    {
        $envContent = $this->readFile();
        $pattern = sprintf('/^%s=(.*)$/m', $this->escapeKey($key));

        if (preg_match($pattern, $envContent, $matches)) {
            return $this->parseValue($matches[1]);
        }

        return $default;
    }

    public function getAll(): array
    {
        $envContent = $this->readFile();
        $lines = explode("\n", $envContent);
        $envVars = [];

        foreach ($lines as $line) {
            if (in_array(trim($line), ['', '0'], true)) {
                continue;
            }
            if (str_starts_with(trim($line), '#')) {
                continue;
            }
            if (preg_match('/^([^=]+)=(.*)$/', $line, $matches)) {
                $key = trim($matches[1]);
                $value = $this->parseValue($matches[2]);
                $envVars[$key] = $value;
            }
        }

        return $envVars;
    }

    public function has(string $key): bool
    {
        return $this->keyExists($key);
    }

    public function backup(?string $name = null): string
    {
        if (! is_dir($this->backupDir)) {
            mkdir($this->backupDir, 0755, true);
        }

        $backupName = $name ?? date('Y-m-d_H-i-s').'.env';
        $backupPath = $this->backupDir.'/'.$backupName;

        copy($this->envFile, $backupPath);

        return $backupPath;
    }

    public function restore(string $backupFile): bool
    {
        $backupPath = $this->backupDir.'/'.$backupFile;

        throw_unless(file_exists($backupPath), Exception::class, sprintf("Backup file '%s' does not exist", $backupFile));

        copy($backupPath, $this->envFile);

        return true;
    }

    public function listBackups(): array
    {
        if (! is_dir($this->backupDir)) {
            return [];
        }

        $backups = scandir($this->backupDir);
        $backups = array_filter($backups, fn ($file): bool => $file !== '.' && $file !== '..');

        return array_values($backups);
    }

    protected function keyExists(string $key): bool
    {
        $envContent = $this->readFile();

        return preg_match(sprintf('/^%s=/m', $this->escapeKey($key)), $envContent) === 1;
    }

    protected function append(string $key, string $value): bool
    {
        $envContent = $this->readFile();

        if (! in_array(trim($envContent), ['', '0'], true)) {
            $envContent .= PHP_EOL;
        }

        $envContent .= sprintf('%s=%s', $key, $value).PHP_EOL;

        return $this->writeFile($envContent);
    }

    protected function readFile(): string
    {
        if (! file_exists($this->envFile)) {
            return '';
        }

        return file_get_contents($this->envFile);
    }

    protected function writeFile(string $content): bool
    {
        return file_put_contents($this->envFile, $content) !== false;
    }

    protected function formatValue($value): string
    {
        $value = strval($value);

        if (str_contains($value, ' ') || str_contains($value, '"')) {
            return '"'.addslashes($value).'"';
        }

        return $value;
    }

    protected function parseValue(string $value): string
    {
        $value = trim($value);

        if ((str_starts_with($value, '"') && str_ends_with($value, '"')) ||
            (str_starts_with($value, "'") && str_ends_with($value, "'"))) {
            $value = substr($value, 1, -1);
            $value = stripslashes($value);
        }

        return $value;
    }

    protected function escapeKey(string $key): string
    {
        return preg_quote($key, '/');
    }

    public function getEnvFile(): string
    {
        return $this->envFile;
    }
}
