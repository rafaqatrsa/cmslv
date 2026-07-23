<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FinancialYearContext
{
    public function id(): ?int
    {
        foreach (config('legacy.session.financial_year_keys', ['financial_year_id', 'year_id']) as $key) {
            $id = session($key);

            if (is_numeric($id)) {
                return (int) $id;
            }
        }

        if (! Schema::hasTable('adcademicyear')) {
            return null;
        }

        return DB::table('adcademicyear')
            ->where('is_active', 'yes')
            ->orWhere('is_active', '1')
            ->value('id');
    }
}
