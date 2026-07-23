<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class FinancialYearMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $financialYearId = null;

        foreach (config('legacy.session.financial_year_keys', ['financial_year_id', 'year_id']) as $key) {
            $value = $request->session()->get($key);

            if (is_numeric($value)) {
                $financialYearId = (int) $value;
                break;
            }
        }

        if ($financialYearId && Schema::hasTable('adcademicyear')) {
            $isOpen = DB::table('adcademicyear')
                ->where('id', $financialYearId)
                ->where(function ($query): void {
                    $query->where('is_active', 'yes')->orWhere('is_active', '1');
                })
                ->exists();

            if (! $isOpen) {
                $request->session()->forget(config('legacy.session.financial_year_keys', ['financial_year_id', 'year_id']));

                return redirect()->route('admin.dashboard')->with('error', 'The selected financial year is no longer available.');
            }
        }

        return $next($request);
    }
}
