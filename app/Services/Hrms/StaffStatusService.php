<?php

namespace App\Services\Hrms;

use App\Models\Hrms\Staff;
use Illuminate\Support\Facades\DB;

class StaffStatusService
{
    public function setActive(Staff $staff, bool $active): Staff
    {
        return DB::transaction(function () use ($staff, $active): Staff {
            $staff->forceFill([
                'is_active' => $active ? 1 : 0,
                'disable_at' => $active ? null : now()->toDateString(),
            ])->save();

            return $staff->refresh();
        });
    }
}
