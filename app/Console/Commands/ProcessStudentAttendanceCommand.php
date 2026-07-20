<?php

namespace App\Console\Commands;

use App\Services\Cron\LegacyCronService;
use Illuminate\Console\Command;

class ProcessStudentAttendanceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:process-students';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process migrated legacy student attendance cron logic.';

    /**
     * Execute the console command.
     */
    public function handle(LegacyCronService $cron): int
    {
        $this->line(json_encode($cron->run('student_attendance'), JSON_PRETTY_PRINT));

        return self::SUCCESS;
    }
}
