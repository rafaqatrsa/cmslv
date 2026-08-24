<?php

use App\Services\Adm\AdmModuleRegistry;

uses(Tests\TestCase::class);

it('maps every known adm legacy module', function () {
    $modules = app(AdmModuleRegistry::class)->all();

    expect($modules)->toHaveCount(36)
        ->and($modules['students']['table'])->toBe('students')
        ->and($modules['student-registrations']['table'])->toBe('students_regd')
        ->and($modules['student-attendance']['table'])->toBe('student_attendences')
        ->and($modules['subject-attendance']['table'])->toBe('student_subject_attendances');
});
