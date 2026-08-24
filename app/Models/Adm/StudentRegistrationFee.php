<?php

namespace App\Models\Adm;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentRegistrationFee extends AdmModel
{
    protected $table = 'student_regd_fee';

    protected $guarded = ['id'];

    public $timestamps = true;

    protected function casts(): array
    {
        return [
            'brc_id' => 'integer',
            'student_regd_id' => 'integer',
            'class_id' => 'integer',
            'session_id' => 'integer',
            'feetype_id' => 'integer',
            'amount' => 'decimal:2',
            'date' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(StudentRegistration::class, 'student_regd_id');
    }
}
