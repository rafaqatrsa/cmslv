<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomeworkSubject extends AcademicModel
{
    public $timestamps = false;

    protected $table = 'homework_subjects';

    public function homework(): BelongsTo
    {
        return $this->belongsTo(Homework::class, 'homework_id');
    }
}
