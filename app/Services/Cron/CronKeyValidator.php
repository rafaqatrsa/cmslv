<?php

namespace App\Services\Cron;

class CronKeyValidator
{
    public function isValid(string $key): bool
    {
        $configuredKey = (string) config('cron.key', '');

        return $configuredKey !== '' && hash_equals($configuredKey, $key);
    }
}
