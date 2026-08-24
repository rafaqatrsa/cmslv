<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountHead extends AccountModel
{
    protected $table = 'accountshead';

    public $timestamps = false;

    protected $fillable = [
        'brc_id',
        'staff_id',
        'accounts_head_id',
        'new_accounts_id',
        'sub_accounts_id',
        'sub_sub_accounts_id',
        'code',
        'name',
        'note',
        'is_active',
        'is_posted',
        'is_system',
    ];

    public function accountType(): BelongsTo
    {
        return $this->belongsTo(AccountType::class, 'accounts_head_id');
    }

    public function accountNew(): BelongsTo
    {
        return $this->belongsTo(AccountNew::class, 'new_accounts_id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Staff::class, 'staff_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Branch::class, 'brc_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'accounts_head_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'accounts_head_id');
    }
}
