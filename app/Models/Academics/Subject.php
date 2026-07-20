<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends AcademicModel
{
    protected $table = 'subjects';

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class, 'subject_id');
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(SubjectGroup::class, 'subject_group_subjects', 'subject_id', 'subject_group_id');
    }
}
