<?php

namespace App\Services\User;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserIndexService
{
    public function __construct(private readonly UserContext $context) {}

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

        $columns = Schema::getColumnListing($module['table']);
        $student = $this->context->selectedStudent($request->integer('student_id') ?: null);
        $studentSession = $student ? $this->context->studentSession($student->id) : null;

        return DB::table($module['table'])
            ->when($request->filled('search'), function (Builder $query) use ($module, $request, $columns): void {
                $searchable = array_values(array_intersect($module['search'], $columns));
                $search = $request->string('search')->toString();

                if ($searchable === []) {
                    return;
                }

                $query->where(function (Builder $query) use ($searchable, $search): void {
                    foreach ($searchable as $column) {
                        $query->orWhere($column, 'like', "%{$search}%");
                    }
                });
            })
            ->when($student && in_array('student_id', $columns, true), fn (Builder $query) => $query->where('student_id', $student->id))
            ->when($studentSession && in_array('student_session_id', $columns, true), fn (Builder $query) => $query->where('student_session_id', $studentSession->id))
            ->when($studentSession && in_array('class_id', $columns, true), fn (Builder $query) => $query->where('class_id', $studentSession->class_id))
            ->when($studentSession && in_array('section_id', $columns, true), fn (Builder $query) => $query->where('section_id', $studentSession->section_id))
            ->when($this->context->branchId() && in_array('brc_id', $columns, true), fn (Builder $query) => $query->where('brc_id', $this->context->branchId()))
            ->when($this->context->academicSessionId() && in_array('session_id', $columns, true), fn (Builder $query) => $query->where('session_id', $this->context->academicSessionId()))
            ->orderByDesc(in_array('created_at', $columns, true) ? 'created_at' : 'id')
            ->paginate(20)
            ->withQueryString();
    }
}
