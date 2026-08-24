<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Domain extends AcademicModel
{
    protected $table = 'domain';

    public function modules(): HasMany
    {
        return $this->hasMany(DomainModulePivot::class, 'domain_id');
    }

    protected function casts(): array
    {
        return [
            'subject_id' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'date',
        ];
    }
}
