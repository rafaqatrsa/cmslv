<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalVoucherDetail extends AccountModel
{
    protected $table = 'journal_voucher_detail';

    const UPDATED_AT = null;

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(JournalVoucher::class, 'journal_voucher_id');
    }

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'debit_amount' => 'decimal:2',
            'credit_amount' => 'decimal:2',
            'created_at' => 'datetime',
        ];
    }
}
