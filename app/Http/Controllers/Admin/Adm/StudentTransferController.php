<?php

namespace App\Http\Controllers\Admin\Adm;

use App\Http\Requests\Admin\Adm\TransferClassSectionRequest;
use App\Models\Adm\Student;
use App\Models\Branch;
use App\Services\AcademicSessionContext;
use App\Services\Adm\StudentTransferService;
use App\Services\BranchContext;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class StudentTransferController extends BaseAdmController
{
    public function index(Request $request): View
    {
        return view('admin.adm.student-transfers.index', $this->viewData($request));
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

    public function transfer(TransferClassSectionRequest $request, StudentTransferService $service): JsonResponse
    {
        $count = $service->transferMany($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Students are successfully transferred.',
            'count' => $count,
        ]);
    }

    /** @return array<string, mixed> */
    private function viewData(Request $request): array
    {
        $branchId = $request->integer('brc_id') ?: app(BranchContext::class)->id();
        $sessionId = app(AcademicSessionContext::class)->id();
        $searched = $request->filled('class_id') && $request->filled('section_id');

        return [
            'branches' => Schema::hasTable('branch') ? Branch::query()->active()->orderBy('name')->get(['id', 'name']) : collect(),
            'classes' => $this->lookup('classes', ['id', 'class'], 'class'),
            'sessions' => $this->lookup('sessions', ['id', 'session'], 'id'),
            'students' => $searched ? $this->students($branchId, $sessionId, $request) : collect(),
            'branchId' => $branchId,
            'sourceSessionId' => $sessionId,
            'selectedBranch' => $request->string('brc_id')->toString() ?: (string) ($branchId ?? ''),
            'selectedClass' => $request->string('class_id')->toString(),
            'selectedSection' => $request->string('section_id')->toString(),
            'searched' => $searched,
        ];
    }

    /** @param array<int, string> $columns */
    private function lookup(string $table, array $columns, string $orderBy): Collection
    {
        return Schema::hasTable($table) ? DB::table($table)->orderBy($orderBy)->get($columns) : collect();
    }

    private function students(?int $branchId, ?int $sessionId, Request $request): Collection
    {
        if (! Schema::hasTable('students') || ! Schema::hasTable('student_session')) {
            return collect();
        }

        return Student::query()
            ->join('student_session', 'student_session.student_id', '=', 'students.id')
            ->join('classes', 'classes.id', '=', 'student_session.class_id')
            ->join('sections', 'sections.id', '=', 'student_session.section_id')
            ->when($branchId, fn ($query) => $query->where('student_session.brc_id', $branchId))
            ->when($sessionId, fn ($query) => $query->where('student_session.session_id', $sessionId))
            ->where('student_session.class_id', $request->integer('class_id'))
            ->where('student_session.section_id', $request->integer('section_id'))
            ->where('students.is_active', 'yes')
            ->orderBy('students.admission_no')
            ->get([
                'students.id', 'students.admission_no', 'students.firstname', 'students.lastname',
                'students.father_name', 'students.dob', 'students.gender',
                'student_session.id as student_session_id', 'student_session.brc_id',
                'classes.class', 'sections.section',
            ]);
    }
}
