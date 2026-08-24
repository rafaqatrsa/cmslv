<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chapter extends AcademicModel
{
    protected $table = 'chapter';

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    protected function casts(): array
    {
        return [
            'class_id' => 'integer',
            'subject_id' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
