<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SettingsKeyGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:key-generator';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the key';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (empty(config('app.key')) || str_starts_with(config('app.key'), 'base64:') === false) {
            Artisan::call('key:generate');
            $this->info("Application key generated.");
        } else {
            $this->info("Application key already exists, skipping generation.");
        }
    }
}