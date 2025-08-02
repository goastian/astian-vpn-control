<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use App\Models\Setting\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

class SettingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:system-start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'upload default settings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Default settings uploaded successfully");
        Setting::setDefaultKeys();
        Artisan::call('migrate --force');
        Artisan::call('settings:key-generator');
        Artisan::call('settings:generate-keys --force');
    }
}
