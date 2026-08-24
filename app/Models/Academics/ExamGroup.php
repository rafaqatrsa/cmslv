<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamGroup extends AcademicModel
{
    protected $table = 'exam_groups';

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class, 'exam_group_id');
    }
}
