<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;

class AcademicSessionContext
{
    public function id(): ?int
    {
        foreach (config('legacy.session.academic_session_keys', ['session_id', 'academic_session_id']) as $key) {
            $sessionId = session($key);

            if (is_numeric($sessionId)) {
                return (int) $sessionId;
            }
        }

        if (! Schema::hasTable((new Setting)->getTable()) || ! Schema::hasColumn((new Setting)->getTable(), 'session_id')) {
            return null;
        }

        return Setting::query()->latest('created_at')->value('session_id');
    }
}
