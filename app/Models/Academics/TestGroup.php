<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Relations\HasMany;

class TestGroup extends AcademicModel
{
    protected $table = 'test_groups';

    public function tests(): HasMany
    {
        return $this->hasMany(Test::class, 'test_group_id');
    }
}
