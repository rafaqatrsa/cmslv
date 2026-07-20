<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContraVoucherDetail extends AccountModel
{
    protected $table = 'contra_voucher_details';

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(ContraVoucher::class, 'voucher_id');
    }

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'dr_amount' => 'decimal:2',
            'cr_amount' => 'decimal:2',
        ];
    }
}
