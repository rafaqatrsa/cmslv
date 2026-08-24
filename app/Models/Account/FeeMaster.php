<?php

namespace App\Models\Account;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeeMaster extends AccountModel
{
    protected $table = 'fee_groups_feetype';

    const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'brc_id' => 'integer',
            'fee_class_group_id' => 'integer',
            'fee_class_id' => 'integer',
            'feetype_id' => 'integer',
            'session_id' => 'integer',
            'month_count' => 'integer',
            'amount' => 'decimal:2',
            'created_at' => 'datetime',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'brc_id');
    }

    public function classGroup(): BelongsTo
    {
        return $this->belongsTo(FeeClassGroup::class, 'fee_class_group_id');
    }

    public function accountHead(): BelongsTo
    {
        return $this->belongsTo(AccountHead::class, 'feetype_id');
    }
}
