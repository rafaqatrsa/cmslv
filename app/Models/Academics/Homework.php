<?php

namespace App\Models\Academics;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Homework extends AcademicModel
{
    public const CREATED_AT = null;
    public const UPDATED_AT = null;

    protected $table = 'homework';

    public function subjects(): HasMany
    {
        return $this->hasMany(HomeworkSubject::class, 'homework_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
