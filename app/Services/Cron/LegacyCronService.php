<?php

namespace App\Services\Cron;

use Illuminate\Support\Facades\Log;

class LegacyCronService
{
    /**
     * @return array<string, mixed>
     */
    public function run(string $job): array
    {
        $summary = [
            'job' => $job,
            'processed' => 0,
            'skipped' => 0,
            'failed' => 0,
            'status' => 'not_migrated',
            'message' => 'Legacy CodeIgniter cron implementation was not present in this workspace.',
        ];

        Log::channel(config('cron.log_channel', config('logging.default')))
            ->warning('Legacy cron job requires source migration before side effects are enabled.', $summary);

        return $summary;
    }
}
