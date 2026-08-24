<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountType extends AccountModel
{
    protected $table = 'accounts_type';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'code',
        'note',
        'is_active',
    ];

    public function newAccounts(): HasMany
    {
        return $this->hasMany(AccountNew::class, 'accounts_type_id')->orderBy('id', 'asc');
    }
}
