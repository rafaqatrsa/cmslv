<?php

namespace App\Console\Commands;

use App\Services\Biometric\BiometricAttendanceService;
use Illuminate\Console\Command;

class SyncBiometricAttendanceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:sync-biometric';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize biometric attendance logs through the migrated integration service.';

    /**
     * Execute the console command.
     */
    public function handle(BiometricAttendanceService $biometricAttendance): int
    {
        $this->line(json_encode($biometricAttendance->sync(), JSON_PRETTY_PRINT));

        return self::SUCCESS;
    }
}
