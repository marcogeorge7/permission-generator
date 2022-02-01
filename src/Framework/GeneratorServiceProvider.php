<?php

namespace PermissionGenerator\Framework;

use Illuminate\Support\ServiceProvider;
use PermissionGenerator\Generator\ConfigLoader;
use PermissionGenerator\Generator\PermissionRepository;

/**
 * @codeCoverageIgnore
 */
class GeneratorServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        include __DIR__ . '/helpers.php';
        if ($this->app->runningInConsole()) {
            $this->commands([GeneratorCommand::class]);
        }

        $this->setupConfigs();
        $this->setupContainer();
    }

    private function setupConfigs(): void
    {
        $default = __DIR__."/config.php";
        $custom = base_path("config/generator.php");

        $this->mergeConfigFrom($default, 'generator');
        $this->publishes([$default => $custom], 'config');
    }

    private function setupContainer(): void
    {
        $this->app->bind(ConfigLoader::class, config('generator.container.config_loader'));
        $this->app->bind(PermissionRepository::class, config('generator.container.permission_repository'));
    }
}
