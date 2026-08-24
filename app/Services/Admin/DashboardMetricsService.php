<?php

namespace App\Services\Admin;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardMetricsService
{
    /**
     * @return array<string, mixed>
     */
    public function for(?int $branchId = null): array
    {
        $enquiries = $this->query('enquiry', $branchId);
        $registrations = $this->query('students_regd', $branchId);
        $students = $this->query('students', $branchId);
        $staff = $this->query('staff', $branchId);

        return [
            'branches' => $this->count('branch'),
            'staff' => $staff?->count() ?? 0,
            'front_pages' => $this->count('front_cms_pages'),
            'front_posts' => $this->count('front_cms_programs'),
            'members' => $this->count('libarary_members', $branchId),
            'notifications' => $this->count('system_notification', $branchId),
            'admission_inquiries' => $enquiries?->count() ?? 0,
            'admission_inquiries_today' => $this->countDate($enquiries, 'date'),
            'admission_inquiries_won' => $this->countWhere($enquiries, 'status', 'won'),
            'registrations' => $registrations?->count() ?? 0,
            'registrations_self' => $this->countWhere($registrations, 'regd_status', 1),
            'registrations_online' => $this->countWhere($registrations, 'regd_status', 2),
            'admissions' => $students?->count() ?? 0,
            'admissions_today' => $this->countDate($students, 'admission_date'),
            'students' => $students?->count() ?? 0,
            'male_students' => $this->countWhere($students, 'gender', 'Male'),
            'female_students' => $this->countWhere($students, 'gender', 'Female'),
            'admin_staff' => $this->countWhere($staff, 'category', 1),
            'teaching_staff' => $this->countWhere($staff, 'category', 2),
            'allied_staff' => $this->countWhere($staff, 'category', 3),
            'male_staff' => $this->countWhere($staff, 'gender', 'Male'),
            'female_staff' => $this->countWhere($staff, 'gender', 'Female'),
            'families' => $this->count('student_sibling', $branchId),
            'complaints' => $this->count('complaint', $branchId),
            'visitors' => $this->count('visitors_purpose', $branchId),
            'purchases' => $this->count('purchases', $branchId),
            'sales' => $this->count('sales', $branchId),
            'enquiry_overview' => $this->enquiryOverview($enquiries),
        ];
    }

    private function count(string $table, ?int $branchId = null): int
    {
        return $this->query($table, $branchId)?->count() ?? 0;
    }

    private function countDate(?Builder $query, string $column): int
    {
        if (! $query || ! Schema::hasColumn($query->from, $column)) {
            return 0;
        }

        return (clone $query)->whereDate($column, today())->count();
    }

    private function countWhere(?Builder $query, string $column, string|int $value): int
    {
        if (! $query || ! Schema::hasColumn($query->from, $column)) {
            return 0;
        }

        return (clone $query)->where($column, $value)->count();
    }

    private function query(string $table, ?int $branchId = null): ?Builder
    {
        if (! Schema::hasTable($table)) {
            return null;
        }

        $query = DB::table($table);

        if ($branchId !== null && Schema::hasColumn($table, 'brc_id')) {
            $query->where('brc_id', $branchId);
        }

        return $query;
    }

    /**
     * @return array<string, array{count: int, percentage: int}>
     */
    private function enquiryOverview(?Builder $query): array
    {
        $statuses = ['active', 'won', 'passive', 'lost', 'dead'];
        $total = $query?->count() ?? 0;

        return collect($statuses)->mapWithKeys(function (string $status) use ($query, $total): array {
            $count = $this->countWhere($query, 'status', $status);

            return [$status => [
                'count' => $count,
                'percentage' => $total > 0 ? (int) round(($count / $total) * 100) : 0,
            ]];
        })->all();
    }
}
