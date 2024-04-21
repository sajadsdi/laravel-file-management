<?php

namespace Sajadsdi\LaravelFileManagement\Concerns;

trait JobToolsTrait
{
    private function dispatchJob(string $job, array $config, ...$jobData)
    {
        if ($config['process_to_queue']) {
            dispatch(new $this->config['jobs'][$job]($config, ...$jobData));
        } else {
            dispatch_sync(new $this->config['jobs'][$job]($config, ...$jobData));
        }
    }

}
