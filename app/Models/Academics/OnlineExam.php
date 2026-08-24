<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Relations\HasMany;

class OnlineExam extends AcademicModel
{
    protected $table = 'onlineexam';

    public function questions(): HasMany
    {
        return $this->hasMany(OnlineExamQuestion::class, 'onlineexam_id');
    }
}
