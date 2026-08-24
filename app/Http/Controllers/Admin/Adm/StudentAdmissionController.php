<?php

namespace App\Http\Controllers\Admin\Adm;

use App\Http\Requests\Admin\Adm\StudentAdmissionRequest;
use App\Models\Adm\Sibling;
use App\Models\Adm\Student;
use App\Models\Adm\StudentDocument;
use App\Models\Adm\StudentSession;
use App\Models\Branch;
use App\Services\BranchContext;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StudentAdmissionController extends BaseAdmController
{
    public function create(Request $request): View
    {
        return view('admin.adm.student-admissions.index', $this->viewData($request));
    }

    public function store(StudentAdmissionRequest $request): RedirectResponse
    {
        $student = DB::transaction(fn (): Student => $this->saveAdmission($request));

        return redirect()->route('admin.adm.student-admissions.create')
            ->with('status', 'Student admission created successfully.')
            ->with('admitted_student_id', $student->id);
    }

    public function edit(Student $student, Request $request): View
    {
        $student->load(['sessions', 'documents']);

        return view('admin.adm.student-admissions.index', $this->viewData($request, $student));
    }

    public function update(StudentAdmissionRequest $request, Student $student): RedirectResponse
    {
        DB::transaction(function () use ($request, $student): void {
            $this->saveAdmission($request, $student);
        });

        return redirect()->route('admin.adm.student-admissions.create')
            ->with('status', 'Student admission updated successfully.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        DB::transaction(function () use ($student): void {
            $student->documents()->delete();
            $student->sessions()->each(function (StudentSession $session): void {
                DB::table('student_fees_assign')->where('student_session_id', $session->id)->delete();
                $session->delete();
            });
            if ($student->image) {
                Storage::disk('public')->delete($student->image);
            }
            DB::table('users')->where('user_id', $student->id)->where('role', 'student')->delete();
            $student->delete();
        });

        return redirect()->route('admin.adm.student-admissions.create')
            ->with('status', 'Student admission deleted successfully.');
    }

    public function classSections(Request $request): JsonResponse
    {
        $classId = $request->integer('class_id');
        $query = DB::table('class_sections')
            ->join('sections', 'sections.id', '=', 'class_sections.section_id')
            ->where('class_sections.class_id', $classId)
            ->where('class_sections.is_active', 'yes')
            ->orderBy('sections.section')
            ->select('sections.id', 'sections.section');

        return response()->json($query->get());
    }

    public function registrationDetail(Request $request): JsonResponse
    {
        $registrationId = $request->integer('regd_id');
        $registration = DB::table('students_regd')
            ->where('id', $registrationId)
            ->whereIn('is_active', ['no', '0'])
            ->first();

        if (! $registration) {
            return response()->json([]);
        }

        return response()->json($registration);
    }

    private function saveAdmission(StudentAdmissionRequest $request, ?Student $student = null): Student
    {
        $data = $request->validated();
        $branchId = $this->branchId($request, $student);
        $sessionId = (int) $data['session_id'];
        $feeMode = $data['fee_mode'] ?? 'monthly';

        $payload = $this->studentPayload($data, $branchId, $student);
        if ($request->hasFile('file')) {
            if ($student?->image) {
                Storage::disk('public')->delete($student->image);
            }
            $payload['image'] = $request->file('file')->storePublicly('admission/students', 'public');
        }

        $student ??= new Student;
        $student->fill($payload)->save();

        $sibling = $this->syncSibling($data, $branchId, $student);
        $parentId = $this->syncParentLogin($branchId, $sibling, $data);
        $student->forceFill(['student_sibling_id' => $sibling->id, 'parent_id' => $parentId])->save();
        $studentSession = $this->syncStudentSession($data, $branchId, $student, $sibling, $sessionId, $feeMode);
        $this->syncFees($data['fee_rows'] ?? [], $branchId, $student, $studentSession, $feeMode, $data);
        $this->syncStudentLogin($branchId, $student);
        $this->syncDocuments($request, $student);

        if ($request->filled('regd_id')) {
            DB::table('students_regd')->where('id', $request->integer('regd_id'))->update(['is_active' => 'yes', 'updated_at' => now()]);
        }

        return $student;
    }

    private function studentPayload(array $data, int $branchId, ?Student $student): array
    {
        $payload = Arr::only($data, [
            'staff_id', 'admission_no', 'adcademicyear_id', 'firstname', 'middlename', 'lastname',
            'district_id', 'tehsils_id', 'area_id', 'guardian_is', 'medium_id', 'previous_school_id',
            'pervious_class', 'current_address', 'permanent_address', 'guardian_occupation', 'guardian_email',
            'gender', 'category_id', 'religion_id', 'mobileno', 'email', 'height', 'weight', 'b_form_no',
            'blood_group', 'other_phone', 'father_name', 'father_country_code', 'father_phone', 'father_cnic',
            'father_occupation', 'father_education_id', 'father_living_id', 'mother_name', 'mother_country_code',
            'mother_phone', 'mother_cnic', 'mother_occupation', 'mother_education_id', 'mother_living_id',
            'guardian_name', 'guardian_relation', 'guardian_country_code', 'guardian_phone', 'guardian_address',
            'note', 'bank_id', 'bank_account_title', 'bank_account_no', 'concession_reason_type_id',
            'concession_remark', 'country_id', 'province_id', 'division_id', 'cast_id', 'skill_id',
        ]);

        $payload['brc_id'] = $branchId;
        $payload['admission_date'] = Carbon::parse($data['admission_date'])->toDateString();
        $payload['dob'] = Carbon::parse($data['dob'])->toDateString();
        $payload['school_leaving_date'] = isset($data['school_leaving_date']) && $data['school_leaving_date'] ? Carbon::parse($data['school_leaving_date'])->toDateString() : null;
        $payload['is_active'] = 'yes';
        $payload['acc_type_id'] = 3;
        $payload['measurement_date'] = now()->toDateString();
        $payload['updated_by'] = auth()->id();
        if (! $student) {
            $payload['created_by'] = auth()->id();
            $payload['roll_no'] = $this->nextRollNumber($branchId, (int) $data['class_id'], (int) $data['section_id'], (int) $data['session_id']);
        }

        return $payload;
    }

    private function syncSibling(array $data, int $branchId, Student $student): Sibling
    {
        $sibling = Sibling::query()->where('sibling_cnic', $data['father_cnic'])->first();
        if (! $sibling) {
            $sibling = Sibling::query()->create([
                'brc_id' => $branchId,
                'sibling_name' => $data['father_name'],
                'sibling_code' => ((int) Sibling::query()->where('brc_id', $branchId)->max('sibling_code')) + 1,
                'sibling_cnic' => $data['father_cnic'],
                'father_country_code' => $data['father_country_code'] ?? null,
                'sibling_phone' => $data['father_phone'],
                'created_by' => auth()->id(),
                'is_active' => 'yes',
            ]);
        } else {
            $sibling->update(['sibling_name' => $data['father_name'], 'sibling_phone' => $data['father_phone'], 'updated_by' => auth()->id()]);
        }

        $student->forceFill(['student_sibling_id' => $sibling->id])->save();

        return $sibling;
    }

    private function syncStudentSession(array $data, int $branchId, Student $student, Sibling $sibling, int $sessionId, string $feeMode): StudentSession
    {
        $session = $student->sessions()->firstOrNew(['session_id' => $sessionId]);
        $session->fill([
            'brc_id' => $branchId,
            'student_sibling_id' => $sibling->id,
            'class_id' => $data['class_id'],
            'section_id' => $data['section_id'],
            'session_date' => now()->toDateString(),
            'fee_mode' => $feeMode,
            'is_alumni' => 1,
            'is_active' => 'yes',
            'created_by' => auth()->id(),
        ])->save();

        return $session;
    }

    private function syncFees(array $rows, int $branchId, Student $student, StudentSession $session, string $feeMode, array $data): void
    {
        if (! Schema::hasTable('student_fees_assign')) {
            return;
        }
        DB::table('student_fees_assign')->where('student_session_id', $session->id)->delete();
        foreach ($rows as $row) {
            if (! is_array($row) || ! Arr::get($row, 'feetype_id')) {
                continue;
            }
            $feeAmount = (float) Arr::get($row, 'fee_amount', 0);
            $currentAmount = (float) Arr::get($row, 'current_amount', $feeAmount);
            DB::table('student_fees_assign')->insert([
                'brc_id' => $branchId,
                'student_id' => $student->id,
                'student_session_id' => $session->id,
                'feetype_id' => (int) $row['feetype_id'],
                'frequency' => Arr::get($row, 'frequency', 'Monthly'),
                'fee_amount' => $feeAmount,
                'discount_amount' => max(0, $feeAmount - $currentAmount),
                'current_amount' => $currentAmount,
                'fee_mode' => $feeMode,
                'note' => Arr::get($row, 'note'),
                'created_by' => auth()->id(),
                'is_active' => 'yes',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function syncStudentLogin(int $branchId, Student $student): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }
        $user = DB::table('users')->where('user_id', $student->id)->where('role', 'student')->first();
        if (! $user) {
            DB::table('users')->insert([
                'brc_id' => $branchId,
                'user_id' => $student->id,
                'sibling_id' => 0,
                'username' => 'std'.$student->id,
                'password' => (string) random_int(100000, 999999),
                'childs' => 0,
                'role' => 'student',
                'lang_id' => 4,
                'currency_id' => 0,
                'verification_code' => '',
                'is_active' => 'yes',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function syncParentLogin(int $branchId, Sibling $sibling, array $data): ?int
    {
        if (! Schema::hasTable('users')) {
            return null;
        }

        $parent = DB::table('users')->where('sibling_id', $sibling->id)->where('role', 'parent')->first();
        if ($parent) {
            return (int) $parent->id;
        }

        return (int) DB::table('users')->insertGetId([
            'brc_id' => $branchId,
            'user_id' => 0,
            'sibling_id' => $sibling->id,
            'username' => 'parent'.$sibling->id,
            'password' => (string) random_int(100000, 999999),
            'childs' => 1,
            'role' => 'parent',
            'lang_id' => 4,
            'currency_id' => 0,
            'verification_code' => '',
            'is_active' => 'yes',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function syncDocuments(StudentAdmissionRequest $request, Student $student): void
    {
        if (! $request->hasFile('document') || ! Schema::hasTable('student_doc')) {
            return;
        }
        StudentDocument::query()->create([
            'student_id' => $student->id,
            'title' => $request->input('document_title'),
            'doc' => $request->file('document')->storePublicly('admission/student-documents/'.$student->id, 'public'),
        ]);
    }

    private function nextRollNumber(int $branchId, int $classId, int $sectionId, int $sessionId): int
    {
        return ((int) DB::table('student_session')->where('brc_id', $branchId)->where('class_id', $classId)->where('section_id', $sectionId)->where('session_id', $sessionId)->max('id')) + 1;
    }

    private function branchId(Request $request, ?Student $student = null): int
    {
        if ($student?->brc_id) {
            return (int) $student->brc_id;
        }
        if ($contextId = app(BranchContext::class)->id()) {
            return (int) $contextId;
        }
        if ($request->integer('brc_id')) {
            return $request->integer('brc_id');
        }
        if (! Schema::hasTable((new Branch)->getTable())) {
            return 0;
        }

        return (int) (Branch::query()->active()->value('id') ?? 0);
    }

    private function viewData(Request $request, ?Student $student = null): array
    {
        $branchId = $this->branchId($request, $student);

        return [
            'student' => $student,
            'branchId' => $branchId,
            'branches' => $this->tableCollection('branch', fn () => Branch::query()->active()->orderBy('name')->get(['id', 'name'])),
            'classes' => $this->tableCollection('classes', fn () => DB::table('classes')->where('is_active', 'yes')->orderBy('class')->get(['id', 'class'])),
            'sections' => $this->tableCollection('sections', fn () => DB::table('sections')->where('is_active', 'yes')->orderBy('section')->get(['id', 'section'])),
            'sessions' => $this->tableCollection('sessions', fn () => DB::table('sessions')->orderByDesc('id')->get(['id', 'session'])),
            'academicYears' => $this->tableCollection('adcademicyear', fn () => DB::table('adcademicyear')->orderBy('name')->get(['id', 'name'])),
            'countries' => $this->tableCollection('country', fn () => DB::table('country')->orderBy('name')->get(['id', 'name', 'telephonePrefix'])),
            'religions' => $this->tableCollection('religion', fn () => DB::table('religion')->where('is_active', 'yes')->orderBy('name')->get(['id', 'name'])),
            'mediums' => $this->tableCollection('medium', fn () => DB::table('medium')->where('is_active', 'yes')->orderBy('name')->get(['id', 'name'])),
            'occupations' => $this->tableCollection('occupation', fn () => DB::table('occupation')->where('is_active', 'yes')->orderBy('name')->get(['id', 'name'])),
            'feeHeads' => $this->tableCollection('accountshead', fn () => DB::table('accountshead')->where('is_active', 'yes')->where('new_accounts_id', 19)->where(function ($query) use ($branchId): void {
                $query->whereNull('brc_id')->orWhere('brc_id', $branchId);
            })->orderBy('name')->get(['id', 'name'])),
            'feeRows' => $student?->sessions?->sortByDesc('id')->first() && Schema::hasTable('student_fees_assign')
                ? DB::table('student_fees_assign')->where('student_session_id', $student->sessions->sortByDesc('id')->first()->id)->get()->map(fn ($fee): array => (array) $fee)->all()
                : [],
            'registrations' => $this->registrationDrop($branchId),
            'students' => $this->studentPaginator($request, $branchId),
            'genders' => ['Male', 'Female', 'Other'],
        ];
    }

    private function registrationDrop(int $branchId): Collection
    {
        if (! Schema::hasTable('students_regd')) {
            return collect();
        }

        return DB::table('students_regd')->when($branchId, fn ($query) => $query->where('brc_id', $branchId))->where('is_active', 'no')->orderByDesc('id')->get();
    }

    private function studentPaginator(Request $request, int $branchId): LengthAwarePaginator
    {
        if (! Schema::hasTable((new Student)->getTable())) {
            return new LengthAwarePaginator([], 0, 15, 1, ['path' => $request->url(), 'query' => $request->query()]);
        }

        return Student::query()->when($branchId, fn ($query) => $query->where('brc_id', $branchId))->when($request->filled('search'), function ($query) use ($request): void {
            $search = $request->string('search')->toString();
            $query->where(fn ($query) => $query->where('admission_no', 'like', "%{$search}%")->orWhere('firstname', 'like', "%{$search}%")->orWhere('lastname', 'like', "%{$search}%")->orWhere('father_name', 'like', "%{$search}%"));
        })->latest('id')->paginate(15)->withQueryString();
    }

    private function tableCollection(string $table, callable $callback): Collection
    {
        return Schema::hasTable($table) ? $callback() : collect();
    }
}
