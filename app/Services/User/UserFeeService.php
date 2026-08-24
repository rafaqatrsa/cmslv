<?php

namespace App\Services\User;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserFeeService
{
    public function __construct(private readonly UserContext $context) {}

    /**
     * @return array{assigned: Collection<int, object>, deposits: Collection<int, object>, totals: array<string, float>, missing: list<string>}
     */
    public function forCurrentStudent(?int $studentId = null): array
    {
        $student = $this->context->selectedStudent($studentId);
        $session = $student ? $this->context->studentSession($student->id) : null;
        $missing = collect(['student_fees_assign', 'student_fees_deposite_details'])
            ->reject(fn (string $table) => Schema::hasTable($table))
            ->values()
            ->all();

        $assigned = collect();
        $deposits = collect();

        if ($student && $session && Schema::hasTable('student_fees_assign')) {
            $assigned = DB::table('student_fees_assign')
                ->where('student_id', $student->id)
                ->where('student_session_id', $session->id)
                ->when($this->context->branchId(), fn ($query) => $query->where('brc_id', $this->context->branchId()))
                ->orderByDesc('id')
                ->get();
        }

        if ($student && $session && Schema::hasTable('student_fees_deposite_details')) {
            $deposits = DB::table('student_fees_deposite_details')
                ->where('student_id', $student->id)
                ->where('student_session_id', $session->id)
                ->when($this->context->branchId(), fn ($query) => $query->where('brc_id', $this->context->branchId()))
                ->orderByDesc('id')
                ->get();
        }

        $assignedAmount = (float) $assigned->sum('current_amount');
        $paidAmount = (float) $deposits->sum('paid_amount');

        return [
            'assigned' => $assigned,
            'deposits' => $deposits,
            'totals' => [
                'assigned_amount' => $assignedAmount,
                'paid_amount' => $paidAmount,
                'balance' => $assignedAmount - $paidAmount,
            ],
            'missing' => $missing,
        ];
    }
}
