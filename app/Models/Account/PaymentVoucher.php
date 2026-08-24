<?php

namespace App\Models\Account;

class PaymentVoucher extends AccountModel
{
    protected $table = 'payments_voucher';

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'debit_amount' => 'decimal:2',
            'credit_amount' => 'decimal:2',
            'created_at' => 'datetime',
            'updated_at' => 'date',
        ];
    }
}
