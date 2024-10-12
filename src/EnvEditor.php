<?php

declare(strict_types=1);

namespace AmdadulHaq\EnvEditor;

class EnvEditor
{
    public static function set(string $key, $value): void
    {
        $envFile = base_path('.env');

        if (file_exists($envFile)) {
            $formattedValue = self::formatValue($value);
            file_put_contents($envFile, PHP_EOL."$key=$formattedValue", FILE_APPEND);
        }
    }

    public static function update(string $key, $value): void
    {
        $envFile = base_path('.env');

        if (file_exists($envFile)) {
            $envContent = file_get_contents($envFile);
            $formattedValue = self::formatValue($value);

            $envContent = preg_replace("/^$key=.*$/m", "$key=$formattedValue", $envContent);
            file_put_contents($envFile, $envContent);
        }
    }

    public static function setOrUpdate(string $key, $value): void
    {
        $envFile = base_path('.env');

        if (file_exists($envFile)) {
            $envContent = file_get_contents($envFile);
            $formattedValue = self::formatValue($value);

            if (strpos($envContent, "$key=") !== false) {
                $envContent = preg_replace("/^$key=.*$/m", "$key=$formattedValue", $envContent);
            } else {
                $envContent .= PHP_EOL."$key=$formattedValue";
            }

            file_put_contents($envFile, $envContent);
        }
    }

    public static function remove(string $keyToRemove): void
    {
        $envFile = base_path('.env');

        if (file_exists($envFile)) {
            $envContent = file_get_contents($envFile);

            $pattern = "/^$keyToRemove=.*$/m";
            $envContent = preg_replace($pattern, '', $envContent);
            $envContent = preg_replace("/^\s*\n/m", '', $envContent);

            file_put_contents($envFile, trim($envContent).PHP_EOL);
        }
    }

    /**
     * Format the value for the .env file.
     * Wraps the value in double quotes only if it contains spaces.
     */
    private static function formatValue($value): string
    {
        // Convert the value to a string to handle all types
        $value = strval($value);

        // Check if the value contains spaces
        if (strpos($value, ' ') !== false) {
            $value = '"'.addslashes($value).'"'; // Wrap and escape quotes if necessary
        }

        return $value;
    }
}
