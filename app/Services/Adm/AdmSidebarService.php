<?php

namespace App\Services\Adm;

use App\Models\PermissionCategory;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

class AdmSidebarService
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function items(): array
    {
        return [
            ['label' => 'Dashboard', 'icon' => 'fa-solid fa-desktop', 'route' => 'admin.dashboard'],
            ['label' => 'Staff Recruitment', 'icon' => 'fa-solid fa-users', 'route' => 'admin.hrms.staff.index'],
            ['label' => 'Internal & External Commn', 'icon' => 'fa-regular fa-comments', 'route' => 'admin.adm.mail-sms.index'],
            ['label' => 'Customer Services Mgmt.', 'icon' => 'fa-solid fa-list-check', 'route' => 'admin.adm.complaints.index'],
            [
                'label' => 'Admission Process',
                'icon' => 'fa-solid fa-user-plus',
                'route' => 'admin.adm.dashboard',
                'children' => $this->admissionChildren(),
            ],
            ['label' => 'Withdrawal Process', 'icon' => 'fa-solid fa-ban', 'route' => 'admin.adm.student-transfers.index'],
            ['label' => 'Attendance Mgmt.', 'icon' => 'fa-regular fa-calendar-check', 'route' => 'admin.adm.attendance.index'],
            ['label' => 'Syllabus Mgmt.', 'icon' => 'fa-regular fa-building', 'route' => 'admin.academics.syllabus.index'],
            ['label' => 'Effective Lesson Planning', 'icon' => 'fa-regular fa-calendar-check', 'route' => 'admin.academics.lessons.index'],
            ['label' => 'Timetable & staffing', 'icon' => 'fa-regular fa-clock', 'route' => 'admin.academics.timetables.index'],
            ['label' => 'Homework', 'icon' => 'fa-solid fa-flask', 'route' => 'admin.academics.homework.index'],
            ['label' => 'Paper Generate', 'icon' => 'fa-regular fa-copy', 'route' => 'admin.academics.paper-generate.index'],
            ['label' => 'Examination', 'icon' => 'fa-regular fa-file-lines', 'route' => 'admin.academics.exam-schedules.index'],
            ['label' => 'Test System', 'icon' => 'fa-regular fa-file', 'route' => 'admin.academics.test-schedules.index'],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function admissionChildren(): array
    {
        if (! Schema::hasTable((new PermissionCategory)->getTable())) {
            return [];
        }

        $categories = PermissionCategory::query()
            ->where('perm_group_id', 18)
            ->whereIn('short_code', [
                'admission_enquiry',
                'student_regd',
                'student',
                'students_directory',
                'siblings_directory',
                'transfer_class_section',
                'promote_student',
            ])
            ->get()
            ->keyBy('short_code');

        return collect([
            ['short_code' => 'admission_enquiry', 'route' => 'admin.adm.enquiries.index', 'icon' => 'fa-solid fa-angles-right'],
            ['short_code' => 'student_regd', 'route' => 'admin.adm.student-registrations.index', 'icon' => 'fa-solid fa-angles-right'],
            ['short_code' => 'student', 'route' => 'admin.adm.student-admissions.create', 'icon' => 'fa-solid fa-angles-right'],
            ['short_code' => 'students_directory', 'route' => 'admin.adm.students.index', 'icon' => 'fa-solid fa-angles-right'],
            ['short_code' => 'siblings_directory', 'route' => 'admin.adm.siblings.index', 'icon' => 'fa-solid fa-angles-right'],
            ['short_code' => 'transfer_class_section', 'route' => 'admin.adm.student-transfers.index', 'icon' => 'fa-solid fa-angles-right'],
            ['short_code' => 'promote_student', 'route' => 'admin.adm.student-promotions.index', 'icon' => 'fa-solid fa-angles-right'],
        ])
            ->filter(fn (array $item): bool => $categories->has($item['short_code']))
            ->map(function (array $item) use ($categories): array {
                $category = $categories->get($item['short_code']);

                return [
                    'label' => $this->displayLabel((string) $category->short_code, (string) $category->name),
                    'short_code' => $category->short_code,
                    'route' => $item['route'],
                    'icon' => $item['icon'],
                    'is_disabled' => $item['route'] === null || ! Route::has($item['route']),
                ];
            })
            ->values()
            ->all();
    }

    public function isAdmissionExpanded(): bool
    {
        return request()->routeIs(
            'admin.adm.dashboard',
            'admin.adm.enquiries.*',
            'admin.adm.student-registrations.*',
            'admin.adm.student-admissions.*',
            'admin.adm.students.*',
            'admin.adm.siblings.*',
            'admin.adm.student-transfers.*',
            'admin.adm.student-promotions.*'
        );
    }

    private function displayLabel(string $shortCode, string $fallback): string
    {
        return match ($shortCode) {
            'admission_enquiry' => 'Admission Enquiry',
            'student_regd' => 'Student Registration',
            'student' => 'Student Admission',
            'students_directory' => 'Students Directory',
            'siblings_directory' => 'Siblings Directory',
            'transfer_class_section' => 'Transfer/Class-section',
            'promote_student' => 'Promote Students',
            default => $fallback,
        };
    }
}
