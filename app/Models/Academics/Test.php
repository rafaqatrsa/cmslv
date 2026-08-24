<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Test extends AcademicModel
{
    protected $table = 'test_group_class_batch_tests';

    public function group(): BelongsTo
    {
        return $this->belongsTo(TestGroup::class, 'test_group_id');
    }
}
