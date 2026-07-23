<?php

namespace App\Services;

use App\Models\Setting;

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

        return Setting::query()->latest('created_at')->value('session_id');
    }
}
