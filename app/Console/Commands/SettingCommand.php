<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use App\Models\Setting\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SettingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:upload';

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
        Setting::defaultSetting();
        $this->info("Default settings uploaded successfully");
        Log::info("Default settings uploaded successfully");
    }
}
