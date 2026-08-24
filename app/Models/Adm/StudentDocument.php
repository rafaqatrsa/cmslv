<?php

namespace App\Models\Adm;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentDocument extends AdmModel
{
    protected $table = 'student_doc';

    public $timestamps = false;

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
