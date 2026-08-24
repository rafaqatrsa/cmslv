<?php

namespace App\Http\Controllers\Admin\Adm;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Adm\StudentPromotionRequest;
use App\Models\Adm\StudentSession;
use App\Models\Branch;
use App\Services\Adm\StudentPromotionService;
use App\Services\AcademicSessionContext;
use App\Services\BranchContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class StudentPromotionController extends Controller
{
    public function __construct(protected readonly StudentPromotionService $promotionService) {}

    public function index(Request $request): View
    {
        return view('admin.adm.student-promotions.index', $this->viewData($request));
    }

    public function store(StudentPromotionRequest $request): RedirectResponse|JsonResponse
    {
        $records = $this->promotionStudents($request);
        $promoted = $this->promotionService->promote($records, $request->validated());

        $redirect = route('admin.adm.student-promotions.index', $request->only([
            'brc_id',
            'source_session_id',
            'source_class_id',
            'source_section_id',
            'target_session_id',
            'target_class_id',
            'target_section_id',
            'search',
        ]));

        if ($request->expectsJson()) {
            return response()->json(['status' => 'success', 'message' => "{$promoted} student(s) promoted successfully.", 'redirect' => $redirect]);
        }

        return redirect()
            ->route('admin.adm.student-promotions.index', $request->only([
                'brc_id',
                'source_session_id',
                'source_class_id',
                'source_section_id',
                'target_session_id',
                'target_class_id',
                'target_section_id',
                'search',
            ]))
            ->with('status', "{$promoted} student(s) promoted successfully.");
    }

    public function sections(Request $request): JsonResponse
    {
        $classId = $request->integer('class_id');

        if (! $classId || ! Schema::hasTable('class_sections')) {
            return response()->json([]);
        }

        return response()->json(DB::table('class_sections')
            ->join('sections', 'sections.id', '=', 'class_sections.section_id')
            ->where('class_sections.class_id', $classId)
            ->whereIn('class_sections.is_active', ['yes', '1', 1])
            ->orderBy('sections.section')
            ->get(['sections.id', 'sections.section']));
    }

    /**
     * @return array<string, mixed>
     */
    private function viewData(Request $request): array
    {
        $records = $this->promotionCandidates($request);

        return [
            'records' => $records,
            'classes' => $this->collectionIfTableExists('classes', fn () => DB::table('classes')->orderBy('class')->get(['id', 'class'])),
            'sessions' => $this->collectionIfTableExists('sessions', fn () => DB::table('sessions')->orderByDesc('id')->get(['id', 'session'])),
            'branches' => $this->collectionIfTableExists('branch', fn () => Branch::query()->active()->orderBy('name')->get(['id', 'name'])),
            'sections' => $this->collectionIfTableExists('sections', fn () => DB::table('sections')->orderBy('section')->get(['id', 'section'])),
            'sourceSessionId' => $this->sourceSessionId($request),
            'sourceClassId' => $request->string('source_class_id')->toString(),
            'sourceSectionId' => $request->string('source_section_id')->toString(),
            'targetSessionId' => $request->string('target_session_id')->toString(),
            'targetClassId' => $request->string('target_class_id')->toString(),
            'targetSectionId' => $request->string('target_section_id')->toString(),
            'search' => $request->string('search')->toString(),
            'branchId' => $this->branchId($request),
        ];
    }

    private function promotionQuery(Request $request): Builder
    {
        $table = (new StudentSession)->getTable();

        if (! Schema::hasTable($table)) {
            return StudentSession::query()->whereRaw('1 = 0');
        }

        $columns = Schema::getColumnListing($table);

        $sourceSessionId = $this->sourceSessionId($request);
        $branchId = $this->branchId($request);

        return StudentSession::query()
            ->when(Schema::hasTable('students'), fn (Builder $query) => $query->with(['student']))
            ->when($sourceSessionId && in_array('session_id', $columns, true), fn (Builder $query) => $query->where('session_id', $sourceSessionId))
            ->when($branchId && in_array('brc_id', $columns, true), fn (Builder $query) => $query->where('brc_id', $branchId))
            ->when($request->filled('source_class_id') && in_array('class_id', $columns, true), fn (Builder $query) => $query->where('class_id', $request->integer('source_class_id')))
            ->when($request->filled('source_section_id') && in_array('section_id', $columns, true), fn (Builder $query) => $query->where('section_id', $request->integer('source_section_id')))
            ->when($request->filled('search') && Schema::hasTable('students'), function (Builder $query) use ($request): void {
                $studentColumns = Schema::getColumnListing('students');
                $searchable = array_values(array_intersect([
                    'admission_no',
                    'firstname',
                    'lastname',
                    'father_name',
                    'mobileno',
                ], $studentColumns));

                if ($searchable === []) {
                    return;
                }

                $search = $request->string('search')->toString();

                $query->whereHas('student', function (Builder $query) use ($searchable, $search): void {
                    foreach ($searchable as $column) {
                        $query->orWhere($column, 'like', "%{$search}%");
                    }
                });
            });
    }

    private function promotionStudents(Request $request): Collection
    {
        $ids = $request->input('check', []);

        if ($ids === []) {
            return collect();
        }

        return \App\Models\Adm\Student::query()->whereIn('id', $ids)->get();
    }

    private function sourceSessionId(Request $request): ?int
    {
        return $request->integer('source_session_id') ?: app(AcademicSessionContext::class)->id();
    }

    private function branchId(Request $request): ?int
    {
        return $request->integer('brc_id') ?: app(BranchContext::class)->id();
    }

    private function promotionCandidates(Request $request): Paginator
    {
        $table = (new StudentSession)->getTable();

        if (! Schema::hasTable($table)) {
            return new Paginator([], 0, 15, 1, [
                'path' => $request->url(),
                'query' => $request->query(),
            ]);
        }

        return $this->promotionQuery($request)
            ->latest('id')
            ->paginate(15)
            ->withQueryString();
    }

    /**
     * @param  callable(): Collection<int, mixed>  $callback
     * @return Collection<int, mixed>
     */
    private function collectionIfTableExists(string $table, callable $callback): Collection
    {
        if (! Schema::hasTable($table)) {
            return collect();
        }

        return $callback();
    }
}
