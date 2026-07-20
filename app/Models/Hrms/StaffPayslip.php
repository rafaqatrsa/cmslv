<?php

namespace App\Models\Hrms;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffPayslip extends HrmsModel
{
    protected $table = 'staff_payslip';

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    protected function casts(): array
    {
        return [
            'net_basic_pay' => 'decimal:2',
            'net_pay' => 'decimal:2',
            'net_pay_ded' => 'decimal:2',
            'net_final_pay' => 'decimal:2',
            'net_salary' => 'decimal:2',
            'salary_month_date' => 'date',
            'payment_date' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
