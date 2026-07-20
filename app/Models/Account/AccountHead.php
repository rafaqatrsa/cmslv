<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountHead extends AccountModel
{
    protected $table = 'accountshead';

    public $timestamps = false;

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'accounts_head_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'accounts_head_id');
    }
}
