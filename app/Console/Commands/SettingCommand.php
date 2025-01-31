<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use App\Models\System\Setting;
use Illuminate\Console\Command;

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
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $data = Setting::defaultSetting();

        foreach ($data as $value) {
            Setting::firstOrCreate(
                [
                    'key' => $value->key
                ],
                [
                    'key' => Str::slug($value->key, "."),
                    'value' => $value->value,
                ]
            );
        }
    }
}
