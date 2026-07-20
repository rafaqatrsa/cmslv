<?php

namespace App\Http\Controllers\Cron;

use App\Http\Controllers\Controller;
use App\Services\Biometric\BiometricAttendanceService;
use App\Services\Cron\CronKeyValidator;
use App\Services\Cron\LegacyCronService;
use Illuminate\Http\JsonResponse;

class CronController extends Controller
{
    public function __construct(
        private readonly CronKeyValidator $keys,
        private readonly LegacyCronService $cron,
        private readonly BiometricAttendanceService $biometricAttendance,
    ) {}

    public function index(string $key): JsonResponse
    {
        return $this->run($key, 'index');
    }

    public function biometricAttendance(string $key): JsonResponse
    {
        abort_unless($this->keys->isValid($key), 404);

        return response()->json($this->biometricAttendance->sync());
    }

    public function studentAttendance(string $key): JsonResponse
    {
        return $this->run($key, 'student_attendance');
    }

    public function autoBackup(string $key): JsonResponse
    {
        return $this->run($key, 'autobackup');
    }

    public function feeReminder(string $key): JsonResponse
    {
        return $this->run($key, 'feereminder');
    }

    public function eventReminder(string $key): JsonResponse
    {
        return $this->run($key, 'eventreminder');
    }

    public function scheduleSmsEmails(string $key): JsonResponse
    {
        return $this->run($key, 'schedulesmsemails');
    }

    private function run(string $key, string $job): JsonResponse
    {
        abort_unless($this->keys->isValid($key), 404);

        return response()->json($this->cron->run($job));
    }
}
