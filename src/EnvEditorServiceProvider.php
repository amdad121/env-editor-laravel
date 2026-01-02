<?php

declare(strict_types=1);

namespace AmdadulHaq\EnvEditor;

use Illuminate\Support\ServiceProvider;

class EnvEditorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(EnvEditor::class, fn (): EnvEditor => new EnvEditor(
            $this->app->basePath('.env')
        ));
    }
}
