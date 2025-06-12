<?php

namespace App\Console\Commands;

use App\Models\Security\KeyGenerator;
use Illuminate\Console\Command;

class GenerateKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "settings:generate-keys {--force : Overwrite existing keys without confirmation}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Generate keys";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $keyGen = new KeyGenerator();
        $force = $this->option('force');

        if (file_exists("$keyGen->path/priv.pem") && !$force) {
            if (!$this->confirm("The keys already exist. Do you want to overwrite them?")) {
                $this->info("Key generation canceled.");
                return;
            }
        }

        $keyGen->generateKeys(true);
        $this->info("Keys successfully generated in $keyGen->path");
    }
}
