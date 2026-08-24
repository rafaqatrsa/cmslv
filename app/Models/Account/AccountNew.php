<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountNew extends AccountModel
{
    protected $table = 'accountsnew';

    public $timestamps = false;

    protected $fillable = [
        'accounts_type_id',
        'code',
        'name',
        'note',
        'is_active',
        'is_system',
    ];

    public function accountType(): BelongsTo
    {
        return $this->belongsTo(AccountType::class, 'accounts_type_id');
    }

    public function accountHeads(): HasMany
    {
        return $this->hasMany(AccountHead::class, 'new_accounts_id');
    }
}
