<?php

namespace App\Models\Account;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeeClassGroup extends AccountModel
{
    protected $table = 'fee_class_groups';

    const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'brc_id' => 'integer',
            'fee_class_id' => 'integer',
            'session_id' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'brc_id');
    }

    public function feeTypes(): HasMany
    {
        return $this->hasMany(FeeMaster::class, 'fee_class_group_id')->orderBy('id', 'asc');
    }
}
