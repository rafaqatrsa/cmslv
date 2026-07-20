<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Relations\HasMany;

class JournalVoucher extends AccountModel
{
    protected $table = 'journal_voucher';

    public function details(): HasMany
    {
        return $this->hasMany(JournalVoucherDetail::class, 'journal_voucher_id');
    }

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }
}
