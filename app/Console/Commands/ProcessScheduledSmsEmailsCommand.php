<?php

namespace App\Console\Commands;

use App\Services\Cron\LegacyCronService;
use Illuminate\Console\Command;

class ProcessScheduledSmsEmailsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'communications:process-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrated legacy scheduled SMS and email cron logic.';

    /**
     * Execute the console command.
     */
    public function handle(LegacyCronService $cron): int
    {
        $this->line(json_encode($cron->run('schedulesmsemails'), JSON_PRETTY_PRINT));

        return self::SUCCESS;
    }
}
