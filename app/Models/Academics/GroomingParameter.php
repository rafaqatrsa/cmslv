<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroomingParameter extends AcademicModel
{
    protected $table = 'groomingparameter';

    public function domain(): BelongsTo
    {
        return $this->belongsTo(GroomingDomain::class, 'domain_id');
    }
}
