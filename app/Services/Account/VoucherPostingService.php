<?php

namespace App\Services\Account;

use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class VoucherPostingService
{
    /**
     * @param  array<int, array{debit?: numeric-string|int|float|null, credit?: numeric-string|int|float|null}>  $entries
     * @return array{debit: string, credit: string}
     */
    public function totals(array $entries): array
    {
        $rows = collect($entries);

        return [
            'debit' => $this->sumMoney($rows, 'debit'),
            'credit' => $this->sumMoney($rows, 'credit'),
        ];
    }

    /**
     * @param  array<int, array{debit?: numeric-string|int|float|null, credit?: numeric-string|int|float|null}>  $entries
     */
    public function assertBalanced(array $entries): void
    {
        $totals = $this->totals($entries);

        if ($this->compareMoney($totals['debit'], $totals['credit']) !== 0) {
            throw ValidationException::withMessages([
                'entries' => 'The voucher debit and credit totals must be equal.',
            ]);
        }
    }

    private function compareMoney(string $left, string $right): int
    {
        if (function_exists('bccomp')) {
            return bccomp($left, $right, 2);
        }

        return number_format((float) $left, 2, '.', '') <=> number_format((float) $right, 2, '.', '');
    }

    private function addMoney(string $left, string $right): string
    {
        if (function_exists('bcadd')) {
            return bcadd($left, $right, 2);
        }

        return number_format(((float) $left) + ((float) $right), 2, '.', '');
    }

    private function moneyString(mixed $value): string
    {
        return number_format((float) ($value ?? 0), 2, '.', '');
    }

    private function sumMoney(Collection $rows, string $column): string
    {
        return $rows->reduce(
            fn (string $carry, array $row): string => $this->addMoney($carry, $this->moneyString($row[$column] ?? 0)),
            '0.00'
        );
    }
}
