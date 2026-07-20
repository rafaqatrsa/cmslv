<?php

use App\Services\User\UserModuleRegistry;

uses(Tests\TestCase::class);

it('maps every known student and parent legacy module', function () {
    $modules = app(UserModuleRegistry::class)->all();

    expect($modules)->toHaveCount(8)
        ->and($modules['dashboard']['route'])->toBe('user.dashboard')
        ->and($modules['attendance']['table'])->toBe('student_attendences')
        ->and($modules['books']['table'])->toBe('books')
        ->and($modules['google-meet']['route'])->toBe('user.google-meet.index')
        ->and($modules['video-tutorials']['table'])->toBe('video_tutorial');
});
