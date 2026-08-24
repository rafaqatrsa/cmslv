<?php

namespace App\Console\Commands;

use App\Services\Cron\LegacyCronService;
use Illuminate\Console\Command;

class RunAutoBackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:auto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrated legacy automatic backup cron logic.';

    /**
     * Execute the console command.
     */
    public function handle(LegacyCronService $cron): int
    {
        $this->line(json_encode($cron->run('autobackup'), JSON_PRETTY_PRINT));

        return self::SUCCESS;
    }
}
