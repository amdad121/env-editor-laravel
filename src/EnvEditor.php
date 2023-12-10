<?php

declare(strict_types=1);

namespace AmdadulHaq\EnvEditor;

class EnvEditor
{
    public static function set($key, $value)
    {
        $envFile = base_path('.env');

        if (file_exists($envFile)) {
            file_put_contents($envFile, PHP_EOL."$key=$value", FILE_APPEND);
        }
    }

    public static function update($key, $value)
    {
        $envFile = base_path('.env');

        if (file_exists($envFile)) {
            $envContent = file_get_contents($envFile);
            $envContent = preg_replace("/^$key=(.*)/m", "$key=$value", $envContent);
            file_put_contents($envFile, $envContent);
        }
    }

    public static function setOrUpdate($key, $value)
    {
        $envFile = base_path('.env');

        if (file_exists($envFile)) {
            $envContent = file_get_contents($envFile);

            // Check if the variable exists, then update; otherwise, set a new one
            if (strpos($envContent, "$key=") !== false) {
                $envContent = preg_replace("/^$key=(.*)/m", "$key=$value", $envContent);
            } else {
                $envContent .= PHP_EOL."$key=$value";
            }

            file_put_contents($envFile, $envContent);
        }
    }

    public static function remove($keyToRemove): void
    {
        $envFile = base_path('.env');

        if (file_exists($envFile)) {
            $envContent = file_get_contents($envFile);

            // Create a regex pattern to match the key-value pair
            $pattern = "/^{$keyToRemove}=.*/m";

            // Remove the matched key-value pair
            $envContent = preg_replace($pattern, '', $envContent);

            // Write the updated content back to the .env file
            file_put_contents($envFile, $envContent);
        }
    }
}
