<?php

namespace Sajadsdi\LaravelFileManagement\Providers;

use Illuminate\Support\ServiceProvider;
use Sajadsdi\LaravelFileManagement\Console\PublishCommand;
use Sajadsdi\LaravelFileManagement\Contracts\FileRepositoryInterface;
use Sajadsdi\LaravelFileManagement\FileManagement;
use Sajadsdi\LaravelFileManagement\Repository\FileRepository;

class FileManagementServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $config = config('file-management');

        $this->app->bind(FileRepositoryInterface::class, FileRepository::class);

        $this->app->singleton(FileManagement::class, function () use ($config) {
            return new FileManagement($config, $this->app->make(FileRepositoryInterface::class));
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->configurePublishing();
            $this->migrationPublishing();
            $this->registerCommands();
        }
    }

    private function configurePublishing()
    {
        $this->publishes([__DIR__ . '/../../config/file-management.php' => config_path('file-management.php')], 'laravel-file-management-configure');
    }

    private function migrationPublishing()
    {
        $this->publishes([__DIR__ . '/../../database/migrations/2023_11_09_220831_create_files_table.php' => database_path('migrations/2023_11_09_220831_create_files_table.php')], 'laravel-file-management-migration');
    }

    private function registerCommands()
    {
        $this->commands([
            PublishCommand::class,
        ]);
    }

}
