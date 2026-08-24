<?php

namespace App\Console\Commands;

use App\Services\Cron\LegacyCronService;
use Illuminate\Console\Command;

class SendEventRemindersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrated legacy event reminder cron logic.';

    /**
     * Execute the console command.
     */
    public function handle(LegacyCronService $cron): int
    {
        $this->line(json_encode($cron->run('eventreminder'), JSON_PRETTY_PRINT));

        return self::SUCCESS;
    }
}
