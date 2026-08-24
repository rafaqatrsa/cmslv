<?php

namespace App\Http\Controllers\Admin\Adm;

use App\Models\Adm\Student;
use App\Models\Branch;
use App\Services\AcademicSessionContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class StudentController extends BaseAdmController
{
    public function index(Request $request): View
    {
        return view('admin.adm.students.index', $this->viewData($request));
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

    public function show(Student $student, Request $request): View
    {
        $sessionId = $this->sessionId($request);
        $student->load(['documents', 'sessions' => fn ($query) => $query
            ->when($sessionId, fn (Builder $query) => $query->where('session_id', $sessionId))
            ->with(['sibling'])]);

        $session = $student->sessions->first();
        $class = $session ? DB::table('classes')->where('id', $session->class_id)->value('class') : null;
        $section = $session ? DB::table('sections')->where('id', $session->section_id)->value('section') : null;
        $branch = $session ? DB::table('branch')->where('id', $session->brc_id)->value('name') : null;

        return view('admin.adm.students.show', compact('student', 'session', 'class', 'section', 'branch'));
    }

    /** @return array<string, mixed> */
    private function viewData(Request $request): array
    {
        $sessionId = $this->sessionId($request);

        return [
            'branches' => $this->collectionIfTableExists('branch', fn () => Branch::query()->active()->orderBy('name')->get(['id', 'name'])),
            'classes' => $this->collectionIfTableExists('classes', fn () => DB::table('classes')->orderBy('class')->get(['id', 'class'])),
            'sections' => collect(),
            'studentOptions' => $this->studentOptions($request, $sessionId),
            'records' => $this->records($request, $sessionId),
            'selectedBranch' => $request->string('brc_id')->toString(),
            'selectedClass' => $request->string('class_id')->toString(),
            'selectedSection' => $request->string('section_id')->toString(),
            'selectedStudent' => $request->string('adm_student_id')->toString(),
            'selectedSession' => (string) ($request->integer('session_id') ?: ($sessionId ?? '')),
            'searched' => $request->hasAny(['class_id', 'adm_student_id']),
        ];
    }

    private function records(Request $request, ?int $sessionId): LengthAwarePaginator
    {
        if (! Schema::hasTable('students') || ! Schema::hasTable('student_session') || ! $request->hasAny(['class_id', 'adm_student_id'])) {
            return new LengthAwarePaginator([], 0, 10, 1, ['path' => $request->url(), 'query' => $request->query()]);
        }

        $query = Student::query()
            ->join('student_session', 'student_session.student_id', '=', 'students.id')
            ->leftJoin('branch', 'branch.id', '=', 'student_session.brc_id')
            ->leftJoin('classes', 'classes.id', '=', 'student_session.class_id')
            ->leftJoin('sections', 'sections.id', '=', 'student_session.section_id')
            ->leftJoin('student_sibling', 'student_sibling.id', '=', 'student_session.student_sibling_id')
            ->leftJoin('categories', 'categories.id', '=', 'students.category_id')
            ->select('students.*', 'student_session.id as student_session_id', 'student_session.brc_id as session_brc_id', 'student_session.class_id', 'student_session.section_id', 'classes.class as class_name', 'sections.section as section_name', 'branch.name as branch_name', 'categories.category as category_name', 'student_sibling.sibling_code', 'student_sibling.sibling_phone', 'student_sibling.sibling_cnic')
            ->when($sessionId, fn (Builder $query) => $query->where('student_session.session_id', $sessionId))
            ->when($request->filled('brc_id'), fn (Builder $query) => $query->where('student_session.brc_id', $request->integer('brc_id')))
            ->when($request->filled('class_id'), fn (Builder $query) => $query->where('student_session.class_id', $request->integer('class_id')))
            ->when($request->filled('section_id'), fn (Builder $query) => $query->where('student_session.section_id', $request->integer('section_id')))
            ->when($request->filled('adm_student_id'), fn (Builder $query) => $query->where('students.id', $request->integer('adm_student_id')))
            ->orderBy('students.admission_no');

        return $query->paginate(10)->withQueryString();
    }

    private function studentOptions(Request $request, ?int $sessionId): Collection
    {
        if (! Schema::hasTable('students') || ! Schema::hasTable('student_session')) {
            return collect();
        }

        return Student::query()
            ->join('student_session', 'student_session.student_id', '=', 'students.id')
            ->when($sessionId, fn (Builder $query) => $query->where('student_session.session_id', $sessionId))
            ->when($request->filled('brc_id'), fn (Builder $query) => $query->where('student_session.brc_id', $request->integer('brc_id')))
            ->where('students.is_active', 'yes')
            ->orderBy('students.admission_no')
            ->get(['students.id as student_id', 'students.admission_no', 'students.firstname', 'students.lastname', 'students.father_name']);
    }

    private function sessionId(Request $request): ?int
    {
        $selected = $request->integer('session_id');

        return $selected ?: app(AcademicSessionContext::class)->id();
    }

    /** @param callable(): Collection<int, mixed> $callback */
    private function collectionIfTableExists(string $table, callable $callback): Collection
    {
        return Schema::hasTable($table) ? $callback() : collect();
    }
}
