<?php

namespace App\Services\Account;

use App\Services\AcademicSessionContext;
use App\Services\BranchContext;
use App\Services\FinancialYearContext;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Schema;

class AccountIndexService
{
    public function __construct(
        private readonly BranchContext $branchContext,
        private readonly AcademicSessionContext $sessionContext,
        private readonly FinancialYearContext $financialYearContext,
    ) {}

    /**
     * @param  array<string, mixed>  $module
     */
    public function paginate(array $module, Request $request): LengthAwarePaginator
    {
        if (! Schema::hasTable($module['table'])) {
            return new Paginator([], 0, 20, 1, [
                'path' => $request->url(),
                'query' => $request->query(),
            ]);
        }

        /** @var class-string<\Illuminate\Database\Eloquent\Model> $model */
        $model = $module['model'];
        /** @var \Illuminate\Database\Eloquent\Model $instance */
        $instance = new $model;
        $columns = Schema::getColumnListing($instance->getTable());

        return $model::query()
            ->when($request->filled('search'), function (Builder $query) use ($module, $request, $columns): void {
                $search = $request->string('search')->toString();
                $searchable = array_values(array_intersect($module['search'], $columns));

                if ($searchable === []) {
                    return;
                }

                $query->where(function (Builder $query) use ($searchable, $search): void {
                    foreach ($searchable as $column) {
                        $query->orWhere($column, 'like', "%{$search}%");
                    }
                });
            })
            ->when($this->branchContext->id() && in_array('brc_id', $columns, true), fn (Builder $query) => $query->where('brc_id', $this->branchContext->id()))
            ->when($this->sessionContext->id() && in_array('session_id', $columns, true), fn (Builder $query) => $query->where('session_id', $this->sessionContext->id()))
            ->when($this->financialYearContext->id() && in_array('financial_year_id', $columns, true), fn (Builder $query) => $query->where('financial_year_id', $this->financialYearContext->id()))
            ->when($this->financialYearContext->id() && in_array('year_id', $columns, true), fn (Builder $query) => $query->where('year_id', $this->financialYearContext->id()))
            ->latest(in_array('created_at', $columns, true) ? 'created_at' : 'id')
            ->paginate(20)
            ->withQueryString();
    }
}
