<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Http\Request;

class AcademicSessionContext
{
    public function __construct(private readonly Request $request) {}

    public function id(): ?int
    {
        $sessionId = $this->request->session()->get('session_id', $this->request->session()->get('academic_session_id'));

        if (is_numeric($sessionId)) {
            return (int) $sessionId;
        }

        return Setting::query()->latest('created_at')->value('session_id');
    }
}
