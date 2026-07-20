<?php

namespace App\Services\Account;

use App\Models\Account\Product;
use Illuminate\Validation\ValidationException;

class InventoryService
{
    public function assertAvailable(Product $product, string $quantity): void
    {
        if ($this->compare((string) $product->quantity, $quantity) < 0) {
            throw ValidationException::withMessages([
                'quantity' => 'The requested quantity exceeds available stock.',
            ]);
        }
    }

    private function compare(string $available, string $required): int
    {
        if (function_exists('bccomp')) {
            return bccomp($available, $required, 2);
        }

        return number_format((float) $available, 2, '.', '') <=> number_format((float) $required, 2, '.', '');
    }
}
