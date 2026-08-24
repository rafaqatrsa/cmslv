<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SubjectGroup extends AcademicModel
{
    public const UPDATED_AT = null;

    protected $table = 'subject_groups';

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'subject_group_subjects', 'subject_group_id', 'subject_id');
    }
}
