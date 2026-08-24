<?php

use App\Services\Teacher\TeacherModuleRegistry;

uses(Tests\TestCase::class);

it('maps every known teacher legacy module', function () {
    $modules = app(TeacherModuleRegistry::class)->all();

    expect($modules)->toHaveCount(16)
        ->and($modules['dashboard']['route'])->toBe('teacher.dashboard')
        ->and($modules['homework']['table'])->toBe('homework')
        ->and($modules['lessons']['table'])->toBe('lesson')
        ->and($modules['student-attendance']['route'])->toBe('teacher.student-attendance.index')
        ->and($modules['timetable']['table'])->toBe('subject_timetable');
});
