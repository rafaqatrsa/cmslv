<?php

namespace App\Services\Teacher;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Schema;

class TeacherIndexService
{
    public function __construct(
        private readonly TeacherContext $context,
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
            ->when($this->context->staffId() && in_array('staff_id', $columns, true), fn (Builder $query) => $query->where('staff_id', $this->context->staffId()))
            ->when($this->context->branchId() && in_array('brc_id', $columns, true), fn (Builder $query) => $query->where('brc_id', $this->context->branchId()))
            ->when($this->context->academicSessionId() && in_array('session_id', $columns, true), fn (Builder $query) => $query->where('session_id', $this->context->academicSessionId()))
            ->latest(in_array('created_at', $columns, true) ? 'created_at' : 'id')
            ->paginate(20)
            ->withQueryString();
    }
}
