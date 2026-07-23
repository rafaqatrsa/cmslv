<?php

use App\Services\AcademicSessionContext;
use App\Services\BranchContext;
use App\Services\FinancialYearContext;
use Illuminate\Http\Request;
use Tests\TestCase;

uses(TestCase::class);

it('defines the legacy compatibility defaults we rely on in phase 0', function () {
    expect(config('legacy.database.host'))->toBe('localhost')
        ->and(config('legacy.database.name'))->toBe('tntlaravel')
        ->and(config('legacy.session.branch_keys'))->toBe(['brc_id', 'branch_id'])
        ->and(config('legacy.session.academic_session_keys'))->toBe(['session_id', 'academic_session_id'])
        ->and(config('legacy.session.financial_year_keys'))->toBe(['financial_year_id', 'year_id']);
});

it('reads the primary branch session key first', function () {
    $request = Request::create('/');
    $request->setLaravelSession(app('session')->driver());
    $request->session()->put('brc_id', 4);
    $request->session()->put('branch_id', 8);

    expect((new BranchContext($request))->id())->toBe(4);
});

it('falls back to the legacy branch alias when needed', function () {
    $request = Request::create('/');
    $request->setLaravelSession(app('session')->driver());
    $request->session()->put('branch_id', 8);

    expect((new BranchContext($request))->id())->toBe(8);
});

it('reads the academic session aliases without needing a database fallback', function () {
    $request = Request::create('/');
    $request->setLaravelSession(app('session')->driver());
    $request->session()->put('academic_session_id', 22);

    expect((new AcademicSessionContext($request))->id())->toBe(22);
});

it('reads the financial year aliases without needing a database fallback', function () {
    session()->put('year_id', 31);

    expect((new FinancialYearContext)->id())->toBe(31);
});
