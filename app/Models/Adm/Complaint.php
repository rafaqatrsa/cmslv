<?php

namespace App\Models\Adm;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Complaint extends AdmModel
{
    protected $table = 'complaint';

    const UPDATED_AT = null;

    public function type(): BelongsTo
    {
        return $this->belongsTo(ComplaintType::class, 'complaint_type');
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(ComplaintSource::class, 'source');
    }

    public function regarding(): BelongsTo
    {
        return $this->belongsTo(ComplaintRegarding::class, 'regarding');
    }

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'follow_up_date' => 'date',
            'created_at' => 'datetime',
        ];
    }
}
