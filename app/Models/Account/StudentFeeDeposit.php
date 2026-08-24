<?php

namespace App\Models\Account;

class StudentFeeDeposit extends AccountModel
{
    protected $table = 'student_fees_deposite_details';

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'due_date' => 'date',
            'date' => 'date',
            'fee_month' => 'date',
            'fee_month_date' => 'date',
            'amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
