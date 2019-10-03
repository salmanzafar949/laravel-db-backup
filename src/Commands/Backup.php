<?php

namespace Salman\DbBackup\Commands;

use Illuminate\Console\Command;
use Salman\DbBackup\Service\DbBackupService;

class Backup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store backup of application database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $output = DbBackupService::KickStartBackup();

        $this->info($output);
    }
}
