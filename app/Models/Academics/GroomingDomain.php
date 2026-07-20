<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Relations\HasMany;

class GroomingDomain extends AcademicModel
{
    protected $table = 'groomingdomain';

    public function parameters(): HasMany
    {
        return $this->hasMany(GroomingParameter::class, 'domain_id');
    }
}
