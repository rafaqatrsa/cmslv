<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Relations\HasMany;

class ContraVoucher extends AccountModel
{
    protected $table = 'contra_voucher';

    public function details(): HasMany
    {
        return $this->hasMany(ContraVoucherDetail::class, 'voucher_id');
    }

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'amount' => 'decimal:2',
        ];
    }
}
