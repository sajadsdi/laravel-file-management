<?php

namespace Sajadsdi\LaravelFileManagement\Concerns;

trait JobToolsTrait
{
    private function dispatchJob(string $action, array $config, array $jobData)
    {
        if ($config['process_to_queue']) {
            dispatch(new $config['jobs'][$action]($config, ...$jobData));
        } else {
            dispatch_sync(new $config['jobs'][$action]($config, ...$jobData));
        }
    }

}
