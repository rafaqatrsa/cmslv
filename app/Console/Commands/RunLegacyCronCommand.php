<?php

namespace App\Console\Commands;

use App\Services\Cron\LegacyCronService;
use Illuminate\Console\Command;

class RunLegacyCronCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'legacy:cron {job=index}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run a migrated legacy CodeIgniter cron job by name.';

    /**
     * Execute the console command.
     */
    public function handle(LegacyCronService $cron): int
    {
        $summary = $cron->run((string) $this->argument('job'));

        $this->line(json_encode($summary, JSON_PRETTY_PRINT));

        return self::SUCCESS;
    }
}
