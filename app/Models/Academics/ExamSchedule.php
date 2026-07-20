<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamSchedule extends AcademicModel
{
    protected $table = 'exam_group_class_batch_exam_subjects';

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class, 'exam_group_class_batch_exams_id');
    }
}
