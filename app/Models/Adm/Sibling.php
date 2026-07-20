<?php

namespace App\Models\Adm;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Sibling extends AdmModel
{
    protected $table = 'student_sibling';

    public function studentSessions(): HasMany
    {
        return $this->hasMany(StudentSession::class, 'student_sibling_id');
    }
}
