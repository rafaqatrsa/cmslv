<?php

use App\Services\Account\VoucherPostingService;
use Illuminate\Validation\ValidationException;

uses(Tests\TestCase::class);

it('accepts balanced voucher entries', function () {
    $service = app(VoucherPostingService::class);

    $service->assertBalanced([
        ['debit' => '100.00', 'credit' => '0.00'],
        ['debit' => '0.00', 'credit' => '100.00'],
    ]);

    expect($service->totals([
        ['debit' => '100.00', 'credit' => '0.00'],
        ['debit' => '0.00', 'credit' => '100.00'],
    ]))->toBe([
        'debit' => '100.00',
        'credit' => '100.00',
    ]);
});

it('rejects unbalanced voucher entries', function () {
    $service = app(VoucherPostingService::class);

    $service->assertBalanced([
        ['debit' => '100.00', 'credit' => '0.00'],
        ['debit' => '0.00', 'credit' => '99.99'],
    ]);
})->throws(ValidationException::class);
