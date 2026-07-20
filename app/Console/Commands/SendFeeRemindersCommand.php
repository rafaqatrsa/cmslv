<?php

namespace App\Console\Commands;

use App\Services\Cron\LegacyCronService;
use Illuminate\Console\Command;

class SendFeeRemindersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:fees';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrated legacy fee reminder cron logic.';

    /**
     * Execute the console command.
     */
    public function handle(LegacyCronService $cron): int
    {
        $this->line(json_encode($cron->run('feereminder'), JSON_PRETTY_PRINT));

        return self::SUCCESS;
    }
}
