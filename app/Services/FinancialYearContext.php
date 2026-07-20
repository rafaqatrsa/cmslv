<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FinancialYearContext
{
    public function id(): ?int
    {
        $id = session('financial_year_id', session('year_id'));

        if ($id) {
            return (int) $id;
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
