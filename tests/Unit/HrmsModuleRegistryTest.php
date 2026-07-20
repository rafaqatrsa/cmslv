<?php

use App\Services\Hrms\HrmsModuleRegistry;

uses(Tests\TestCase::class);

it('maps every known hrms legacy module', function () {
    $modules = app(HrmsModuleRegistry::class)->all();

    expect($modules)->toHaveCount(4)
        ->and($modules['staff']['table'])->toBe('staff')
        ->and($modules['documents']['table'])->toBe('staff')
        ->and($modules['manual']['table'])->toBe('manual_supporthrm')
        ->and($modules['dashboard']['route'])->toBe('admin.hrms.dashboard');
});
