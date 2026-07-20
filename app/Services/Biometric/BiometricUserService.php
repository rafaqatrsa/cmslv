<?php

namespace App\Services\Biometric;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BiometricUserService
{
    public function isAuthorized(Request $request): bool
    {
        $configuredKey = (string) config('biometric.key', '');

        if ($configuredKey === '') {
            return true;
        }

        $providedKey = (string) ($request->header('X-Biometric-Key') ?: $request->input('key') ?: $request->input('device_key'));

        return $providedKey !== '' && hash_equals($configuredKey, $providedKey);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function users(): array
    {
        if (! Schema::hasTable('students')) {
            return [];
        }

        return DB::table('students')
            ->select(['id', 'admission_no', 'firstname', 'middlename', 'lastname', 'brc_id', 'is_active'])
            ->where(function ($query): void {
                $query->where('is_active', 'yes')->orWhere('is_active', '1');
            })
            ->orderBy('id')
            ->limit((int) config('biometric.user_limit', 1000))
            ->get()
            ->map(fn (object $student): array => [
                'id' => $student->id,
                'device_user_id' => $student->id,
                'admission_no' => $student->admission_no,
                'name' => trim(implode(' ', array_filter([$student->firstname, $student->middlename, $student->lastname]))),
                'role' => 'student',
                'branch_id' => $student->brc_id,
                'status' => $student->is_active,
            ])
            ->all();
    }
}
