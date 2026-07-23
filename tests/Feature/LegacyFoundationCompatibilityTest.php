<?php

use App\Models\Branch;
use App\Models\Setting;
use App\Services\AcademicSessionContext;
use App\Services\BranchContext;
use App\Services\FinancialYearContext;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    $this->withoutVite();
});

it('resolves legacy session aliases through the shared context services', function () {
    session([
        'branch_id' => 12,
        'academic_session_id' => 23,
        'year_id' => 34,
    ]);

    expect(app(BranchContext::class)->id())->toBe(12)
        ->and(app(AcademicSessionContext::class)->id())->toBe(23)
        ->and(app(FinancialYearContext::class)->id())->toBe(34);
});

it('forgets every legacy branch alias when the selected branch is inactive', function () {
    Schema::create('branch', function (Blueprint $table): void {
        $table->increments('id');
        $table->string('name')->nullable();
        $table->string('is_active')->nullable();
        $table->timestamps();
    });

    Branch::query()->create([
        'id' => 7,
        'name' => 'Inactive Branch',
        'is_active' => 'no',
    ]);

    Route::middleware(['web', 'branch'])->get('/_phase0/branch', fn () => 'ok');

    $response = $this
        ->withSession([
            'brc_id' => 7,
            'branch_id' => 7,
        ])
        ->get('/_phase0/branch');

    $response->assertRedirect(route('admin.dashboard', absolute: false));
    expect(session('brc_id'))->toBeNull()
        ->and(session('branch_id'))->toBeNull();
});

it('forgets every financial year alias when the selected year is inactive', function () {
    Schema::create('adcademicyear', function (Blueprint $table): void {
        $table->increments('id');
        $table->string('name')->nullable();
        $table->string('is_active')->nullable();
        $table->timestamps();
    });

    Route::middleware(['web', 'financial.year'])->get('/_phase0/year', fn () => 'ok');

    $response = $this
        ->withSession([
            'financial_year_id' => 9,
            'year_id' => 9,
        ])
        ->get('/_phase0/year');

    $response->assertRedirect(route('admin.dashboard', absolute: false));
    expect(session('financial_year_id'))->toBeNull()
        ->and(session('year_id'))->toBeNull();
});

it('falls back to the system settings session id when no academic session alias exists', function () {
    Schema::create('system_settings', function (Blueprint $table): void {
        $table->increments('id');
        $table->integer('session_id')->nullable();
        $table->timestamps();
    });

    Setting::query()->create([
        'session_id' => 55,
    ]);

    expect(app(AcademicSessionContext::class)->id())->toBe(55);
});
