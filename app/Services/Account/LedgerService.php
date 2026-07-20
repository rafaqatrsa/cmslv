<?php

namespace App\Services\Account;

class LedgerService
{
    public function __construct(
        private readonly VoucherPostingService $voucherPostingService,
    ) {}

    /**
     * @param  array<int, array{debit?: numeric-string|int|float|null, credit?: numeric-string|int|float|null}>  $entries
     */
    public function assertEntriesCanBePosted(array $entries): void
    {
        $this->voucherPostingService->assertBalanced($entries);
    }
}
