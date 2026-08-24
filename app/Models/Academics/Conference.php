<?php

namespace App\Models\Academics;

use App\Models\Branch;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Conference extends AcademicModel
{
    public const UPDATED_AT = null;

    protected $table = 'conferences';

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'brc_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    protected function casts(): array
    {
        return [
            'brc_id' => 'integer',
            'staff_id' => 'integer',
            'date' => 'datetime',
            'duration' => 'integer',
            'session_id' => 'integer',
            'status' => 'integer',
            'created_at' => 'datetime',
        ];
    }
}
