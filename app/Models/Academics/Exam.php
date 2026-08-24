<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends AcademicModel
{
    protected $table = 'exam_group_class_batch_exams';

    public function group(): BelongsTo
    {
        return $this->belongsTo(ExamGroup::class, 'exam_group_id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(ExamSchedule::class, 'exam_group_class_batch_exams_id');
    }
}
