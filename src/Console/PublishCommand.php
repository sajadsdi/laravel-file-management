<?php

namespace Sajadsdi\LaravelFileManagement\Console;

use Illuminate\Console\Command;

class PublishCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file-management:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish Advanced Laravel File Management configure and migration!';

    /**
     * Execute the console command.
     *
     * @return int|null
     */
    public function handle()
    {
        $this->info('Publishing Advanced Laravel File Management ...');
        $this->publish();
        return null;
    }

    private function publish()
    {
        $this->comment('Publishing configure ...');
        $this->call('vendor:publish', ['--tag' => "laravel-file-management-configure"]);

        $this->comment('Publishing migration ...');
        $this->call('vendor:publish', ['--tag' => "laravel-file-management-migration"]);
    }
}
