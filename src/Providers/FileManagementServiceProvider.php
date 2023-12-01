<?php

namespace Sajadsdi\LaravelFileManagement\Providers;

use Illuminate\Support\ServiceProvider;
use Sajadsdi\LaravelFileManagement\Contracts\FileRepositoryInterface;
use Sajadsdi\LaravelFileManagement\FileManagement;
use Sajadsdi\LaravelFileManagement\Repository\FileRepository;
use Sajadsdi\LaravelFileManagement\Services\ImageService;

class FileManagementServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $config = config('file-management');

        $this->app->bind(FileRepositoryInterface::class,FileRepository::class);

        $this->app->singleton(FileManagement::class,function () use ($config){
            return new FileManagement($config, $this->app->make(FileRepositoryInterface::class));
        });

        $this->app->singleton(ImageService::class,function () use ($config) {
            return new ImageService($config['types']['image']['image_process_driver']);
        });
    }

    public function boot(): void
    {

    }
}
