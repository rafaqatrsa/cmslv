<?php

namespace App\Services\Biometric;

use Illuminate\Support\Facades\Log;

class BiometricAttendanceService
{
    /**
     * @return array<string, mixed>
     */
    public function sync(): array
    {
        $summary = [
            'processed' => 0,
            'skipped' => 0,
            'failed' => 0,
            'status' => 'not_migrated',
            'message' => 'Biometric provider protocol and duplicate-log rules were not present in this workspace.',
        ];

        Log::warning('Biometric attendance sync requires legacy provider migration before imports are enabled.', $summary);

        return $summary;
    }
}
