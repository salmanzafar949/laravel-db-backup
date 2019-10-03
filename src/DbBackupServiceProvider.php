<?php

namespace Salman\DbBackup;

use Illuminate\Support\ServiceProvider;
use Salman\DbBackup\Commands\Backup;

class DbBackupServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            Backup::class
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->LoadAndMergeConfig();
    }

    public function LoadAndMergeConfig()
    {
        $this->mergeConfigFrom(__DIR__.'/config/dbbackup.php','dbbackup');
        $this->publishes([
            __DIR__.'/config/dbbackup.php' => config_path('dbbackup.php'),
        ]);
    }
}
