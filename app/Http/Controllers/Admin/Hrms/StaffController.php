<?php

namespace App\Http\Controllers\Admin\Hrms;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Hrms\Staff;
use App\Models\Role;
use App\Models\RoleBranch;
use App\Services\BranchContext;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function __construct(
        private readonly BranchContext $branchContext,
    ) {}

    public function index(Request $request): View
    {
        $selectedBranchId = $this->resolveBranchId($request);
        $branches = Branch::query()->orderBy('name')->get(['id', 'name']);
        $roles = $this->rolesForBranch($selectedBranchId);
        $records = $this->staffDirectoryQuery($request, $selectedBranchId)->paginate(20)->withQueryString();

        return view('admin.hrms.staff.index', [
            'branches' => $branches,
            'roles' => $roles,
            'records' => $records,
            'selectedBranchId' => $selectedBranchId,
            'selectedRoleId' => $request->integer('role') ?: null,
            'selectedSearchField' => $request->string('selected_value_staff')->toString() ?: 'staff_id',
            'searchText' => trim($request->string('text_staff')->toString()),
        ]);
    }

    public function profile(int $staffId, Request $request): View
    {
        $staff = $this->staffDirectoryQuery($request, null)
            ->where('staff.id', $staffId)
            ->firstOrFail();

        return view('admin.hrms.staff.profile', [
            'staff' => $this->transformStaffRecord($staff),
            'academicRecords' => $this->academicRecords($staffId),
            'certificationRecords' => $this->certificationRecords($staffId),
            'experienceRecords' => $this->experienceRecords($staffId),
            'payRecords' => $this->payRecords($staffId),
            'leaveRecords' => $this->leaveRecords($staffId),
            'documentRecords' => $this->documentRecords($staff),
        ]);
    }

    public function create(?int $branchId, Request $request): View
    {
        $selectedBranchId = $branchId ?: $this->resolveBranchId($request);

        return view('admin.hrms.staff.create', $this->staffFormData($selectedBranchId) + [
            'selectedBranchId' => $selectedBranchId,
            'generatedEmployeeId' => $this->generateEmployeeId($selectedBranchId),
        ]);
    }

    public function store(?int $branchId, Request $request): RedirectResponse
    {
        $request->merge([
            'role_id' => $request->input('role_id', $request->input('role')),
            'email' => $request->input('email', $request->input('username')),
            'contact_no' => $request->input('contact_no', $request->input('contactno')),
            'emergency_contact_no' => $request->input('emergency_contact_no', $request->input('emergency_no')),
            'local_address' => $request->input('local_address', $request->input('address')),
            'iban_code' => $request->input('iban_code', $request->input('IBAN_code')),
            'month_security' => $request->input('month_security', $request->input('per_month_security')),
        ]);

        $selectedBranchId = $request->integer('brc_id') ?: $branchId ?: $this->resolveBranchId($request);

        $validated = $request->validate([
            'brc_id' => ['required', 'integer', Rule::exists('branch', 'id')],
            'employee_id' => [
                'required',
                'string',
                'max:200',
                Rule::unique('staff')->where(fn (QueryBuilder $query) => $query->where('brc_id', $selectedBranchId)),
            ],
            'category' => ['required', 'integer', Rule::in([1, 2, 3])],
            'role_id' => ['required', 'integer', Rule::exists('roles_branch', 'id')],
            'designation' => ['nullable', 'integer'],
            'department' => ['nullable', 'integer'],
            'name' => ['required', 'string', 'max:200'],
            'surname' => ['nullable', 'string', 'max:200'],
            'father_name' => ['nullable', 'string', 'max:200'],
            'cnic' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:200'],
            'gender' => ['required', 'string', 'max:50'],
            'dob' => ['required', 'date'],
            'date_of_joining' => ['required', 'date'],
            'contact_no' => ['nullable', 'string', 'max:200'],
            'emergency_contact_no' => ['nullable', 'string', 'max:200'],
            'whatsapp_no' => ['nullable', 'string', 'max:255'],
            'marital_status' => ['nullable', 'string', 'max:100'],
            'contract_type' => ['nullable', 'string', 'max:100'],
            'shift' => ['nullable', 'string', 'max:100'],
            'location' => ['nullable', 'string', 'max:100'],
            'local_address' => ['nullable', 'string', 'max:300'],
            'permanent_address' => ['nullable', 'string', 'max:200'],
            'note' => ['nullable', 'string', 'max:200'],
            'account_title' => ['nullable', 'string', 'max:200'],
            'bank_account_no' => ['nullable', 'string', 'max:200'],
            'bank_name' => ['nullable', 'string', 'max:200'],
            'iban_code' => ['nullable', 'string', 'max:200'],
            'bank_branch' => ['nullable', 'string', 'max:100'],
            'total_security' => ['nullable', 'string', 'max:200'],
            'month_security' => ['nullable', 'string', 'max:200'],
            'facebook' => ['nullable', 'string', 'max:255'],
            'twitter' => ['nullable', 'string', 'max:255'],
            'linkedin' => ['nullable', 'string', 'max:255'],
            'instagram' => ['nullable', 'string', 'max:255'],
            'file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp'],
            'first_doc' => ['nullable', 'file'],
            'second_doc' => ['nullable', 'file'],
            'fourth_doc' => ['nullable', 'file'],
            'eduinst.*' => ['nullable', 'integer'],
            'edufrom.*' => ['nullable', 'integer'],
            'eduto.*' => ['nullable', 'integer'],
            'edudegree.*' => ['nullable', 'integer'],
            'edumaxmark.*' => ['nullable', 'numeric'],
            'eduobtmark.*' => ['nullable', 'numeric'],
            'edugrd.*' => ['nullable', 'string', 'max:255'],
            'cerinst.*' => ['nullable', 'integer'],
            'certrining.*' => ['nullable', 'integer'],
            'cerfrom.*' => ['nullable', 'integer'],
            'certo.*' => ['nullable', 'integer'],
            'cerobtmark.*' => ['nullable', 'numeric'],
            'cergrd.*' => ['nullable', 'string', 'max:255'],
            'exporg.*' => ['nullable', 'integer'],
            'exppost.*' => ['nullable', 'integer'],
            'expcontact.*' => ['nullable', 'string', 'max:255'],
            'expfrom.*' => ['nullable', 'integer'],
            'expto.*' => ['nullable', 'integer'],
            'expsalary.*' => ['nullable', 'numeric'],
            'explereason.*' => ['nullable', 'string'],
            'salary_type.*' => ['nullable', 'integer'],
            'frequency.*' => ['nullable', 'string', 'max:255'],
            'salary_amount.*' => ['nullable', 'numeric'],
            'salary_ded_type.*' => ['nullable', 'integer'],
            'salary_ded_amount.*' => ['nullable', 'numeric'],
        ]);

        $plainPassword = (string) random_int(100000, 999999);
        $userId = auth()->id() ?? 0;

        $staff = Staff::query()->create([
            'brc_id' => $validated['brc_id'],
            'category' => $validated['category'],
            'role_id' => $validated['role_id'],
            'employee_id' => $validated['employee_id'],
            'lang_id' => 0,
            'department' => (int) ($validated['department'] ?? 0),
            'designation' => (int) ($validated['designation'] ?? 0),
            'name' => $validated['name'],
            'surname' => $validated['surname'] ?? '',
            'father_name' => $validated['father_name'] ?? '',
            'contact_no' => $validated['contact_no'] ?? '',
            'emergency_contact_no' => $validated['emergency_contact_no'] ?? '',
            'whatsapp_no' => $validated['whatsapp_no'] ?? '',
            'email' => $validated['email'],
            'dob' => $validated['dob'],
            'cnic' => $validated['cnic'] ?? '',
            'marital_status' => $validated['marital_status'] ?? '',
            'date_of_joining' => $validated['date_of_joining'],
            'date_of_leaving' => '0000-00-00',
            'local_address' => $validated['local_address'] ?? '',
            'permanent_address' => $validated['permanent_address'] ?? '',
            'note' => $validated['note'] ?? '',
            'image' => $this->storeLegacyUpload($request->file('file'), 'staff/image'),
            'password' => Hash::make($plainPassword),
            'ch_password' => $plainPassword,
            'gender' => $validated['gender'],
            'account_title' => $validated['account_title'] ?? '',
            'bank_account_no' => $validated['bank_account_no'] ?? '',
            'bank_name' => $validated['bank_name'] ?? '',
            'IBAN_code' => $validated['iban_code'] ?? '',
            'bank_branch' => $validated['bank_branch'] ?? '',
            'contract_type' => $validated['contract_type'] ?? '',
            'shift' => $validated['shift'] ?? '',
            'location' => $validated['location'] ?? '',
            'total_security' => $validated['total_security'] ?? '',
            'month_security' => $validated['month_security'] ?? '',
            'facebook' => $validated['facebook'] ?? '',
            'twitter' => $validated['twitter'] ?? '',
            'linkedin' => $validated['linkedin'] ?? '',
            'instagram' => $validated['instagram'] ?? '',
            'resume' => $this->storeLegacyUpload($request->file('first_doc'), 'staff/documents'),
            'joining_letter' => $this->storeLegacyUpload($request->file('second_doc'), 'staff/documents'),
            'other_document_file' => $this->storeLegacyUpload($request->file('fourth_doc'), 'staff/documents'),
            'user_id' => $userId,
            'is_active' => 1,
            'verification_code' => bin2hex(random_bytes(8)),
            'disable_at' => null,
            'created_by' => $userId,
            'updated_by' => $userId,
        ]);

        $this->persistAcademicRows($request, (int) $staff->id, $validated['brc_id'], $userId);
        $this->persistCertificationRows($request, (int) $staff->id, $validated['brc_id'], $userId);
        $this->persistExperienceRows($request, (int) $staff->id, $validated['brc_id'], $userId);
        $this->persistPayrollRows($request, (int) $staff->id, $validated['brc_id'], $userId);

        return redirect()
            ->route('admin.hrms.staff.index', ['brc_id' => $validated['brc_id']])
            ->with('success', 'Staff record created successfully.');
    }

    public function edit(int $staffId, Request $request): View
    {
        $staff = Staff::query()->findOrFail($staffId);
        $selectedBranchId = (int) $staff->brc_id;

        return view('admin.hrms.staff.edit', $this->staffFormData($selectedBranchId) + [
            'staff' => $staff,
            'selectedBranchId' => $selectedBranchId,
            'academicRows' => $this->editableAcademicRows($staffId),
            'certificationRows' => $this->editableCertificationRows($staffId),
            'experienceRows' => $this->editableExperienceRows($staffId),
            'payRows' => $this->editablePayRows($staffId, false),
            'payDeductionRows' => $this->editablePayRows($staffId, true),
            'cancelUrl' => route('admin.hrms.staff.index', ['brc_id' => $request->integer('brc_id') ?: $selectedBranchId], false),
        ]);
    }

    public function update(Request $request, int $staffId): RedirectResponse
    {
        $staff = Staff::query()->findOrFail($staffId);

        $request->merge([
            'role_id' => $request->input('role_id', $request->input('role')),
            'email' => $request->input('email', $request->input('username')),
            'contact_no' => $request->input('contact_no', $request->input('contactno')),
            'emergency_contact_no' => $request->input('emergency_contact_no', $request->input('emergency_no')),
            'local_address' => $request->input('local_address', $request->input('address')),
            'iban_code' => $request->input('iban_code', $request->input('IBAN_code')),
            'month_security' => $request->input('month_security', $request->input('per_month_security')),
        ]);

        $validated = $request->validate([
            'brc_id' => ['required', 'integer', Rule::exists('branch', 'id')],
            'role_id' => ['required', 'integer', Rule::exists('roles_branch', 'id')],
            'employee_id' => ['required', 'string', 'max:200'],
            'category' => ['required', 'integer', Rule::in([1, 2, 3])],
            'name' => ['required', 'string', 'max:200'],
            'surname' => ['nullable', 'string', 'max:200'],
            'father_name' => ['nullable', 'string', 'max:200'],
            'department' => ['nullable', 'integer'],
            'designation' => ['nullable', 'integer'],
            'cnic' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:200'],
            'gender' => ['required', 'string', 'max:50'],
            'whatsapp_no' => ['nullable', 'string', 'max:255'],
            'marital_status' => ['nullable', 'string', 'max:100'],
            'contact_no' => ['nullable', 'string', 'max:200'],
            'emergency_contact_no' => ['nullable', 'string', 'max:200'],
            'dob' => ['nullable', 'date'],
            'date_of_joining' => ['nullable', 'date'],
            'contract_type' => ['nullable', 'string', 'max:100'],
            'shift' => ['nullable', 'string', 'max:100'],
            'location' => ['nullable', 'string', 'max:100'],
            'local_address' => ['nullable', 'string', 'max:300'],
            'permanent_address' => ['nullable', 'string', 'max:200'],
            'note' => ['nullable', 'string', 'max:200'],
            'account_title' => ['nullable', 'string', 'max:200'],
            'bank_account_no' => ['nullable', 'string', 'max:200'],
            'bank_name' => ['nullable', 'string', 'max:200'],
            'iban_code' => ['nullable', 'string', 'max:200'],
            'bank_branch' => ['nullable', 'string', 'max:100'],
            'total_security' => ['nullable', 'string', 'max:200'],
            'month_security' => ['nullable', 'string', 'max:200'],
            'facebook' => ['nullable', 'string', 'max:255'],
            'twitter' => ['nullable', 'string', 'max:255'],
            'linkedin' => ['nullable', 'string', 'max:255'],
            'instagram' => ['nullable', 'string', 'max:255'],
            'file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp'],
            'first_doc' => ['nullable', 'file'],
            'second_doc' => ['nullable', 'file'],
            'fourth_doc' => ['nullable', 'file'],
            'eduinst.*' => ['nullable', 'integer'],
            'edufrom.*' => ['nullable', 'integer'],
            'eduto.*' => ['nullable', 'integer'],
            'edudegree.*' => ['nullable', 'integer'],
            'edumaxmark.*' => ['nullable', 'numeric'],
            'eduobtmark.*' => ['nullable', 'numeric'],
            'edugrd.*' => ['nullable', 'string', 'max:255'],
            'cerinst.*' => ['nullable', 'integer'],
            'certrining.*' => ['nullable', 'integer'],
            'cerfrom.*' => ['nullable', 'integer'],
            'certo.*' => ['nullable', 'integer'],
            'cerobtmark.*' => ['nullable', 'numeric'],
            'cergrd.*' => ['nullable', 'string', 'max:255'],
            'exporg.*' => ['nullable', 'integer'],
            'exppost.*' => ['nullable', 'integer'],
            'expcontact.*' => ['nullable', 'string', 'max:255'],
            'expfrom.*' => ['nullable', 'integer'],
            'expto.*' => ['nullable', 'integer'],
            'expsalary.*' => ['nullable', 'numeric'],
            'explereason.*' => ['nullable', 'string'],
            'salary_type.*' => ['nullable', 'integer'],
            'frequency.*' => ['nullable', 'string', 'max:255'],
            'salary_amount.*' => ['nullable', 'numeric'],
            'salary_ded_type.*' => ['nullable', 'integer'],
            'salary_ded_amount.*' => ['nullable', 'numeric'],
            'is_active' => ['required', 'integer', Rule::in([0, 1])],
        ]);

        $userId = auth()->id() ?? 0;

        $staff->fill([
            'brc_id' => $validated['brc_id'],
            'role_id' => $validated['role_id'],
            'employee_id' => $validated['employee_id'],
            'category' => $validated['category'],
            'department' => (int) ($validated['department'] ?? 0),
            'designation' => (int) ($validated['designation'] ?? 0),
            'name' => $validated['name'],
            'surname' => $validated['surname'] ?? '',
            'father_name' => $validated['father_name'] ?? '',
            'cnic' => $validated['cnic'] ?? '',
            'email' => $validated['email'],
            'gender' => $validated['gender'],
            'dob' => $validated['dob'],
            'date_of_joining' => $validated['date_of_joining'],
            'contact_no' => $validated['contact_no'] ?? '',
            'emergency_contact_no' => $validated['emergency_contact_no'] ?? '',
            'whatsapp_no' => $validated['whatsapp_no'] ?? '',
            'marital_status' => $validated['marital_status'] ?? '',
            'local_address' => $validated['local_address'] ?? '',
            'permanent_address' => $validated['permanent_address'] ?? '',
            'contract_type' => $validated['contract_type'] ?? '',
            'shift' => $validated['shift'] ?? '',
            'location' => $validated['location'] ?? '',
            'total_security' => $validated['total_security'] ?? '',
            'month_security' => $validated['month_security'] ?? '',
            'account_title' => $validated['account_title'] ?? '',
            'bank_account_no' => $validated['bank_account_no'] ?? '',
            'bank_name' => $validated['bank_name'] ?? '',
            'IBAN_code' => $validated['iban_code'] ?? '',
            'bank_branch' => $validated['bank_branch'] ?? '',
            'facebook' => $validated['facebook'] ?? '',
            'twitter' => $validated['twitter'] ?? '',
            'linkedin' => $validated['linkedin'] ?? '',
            'instagram' => $validated['instagram'] ?? '',
            'note' => $validated['note'] ?? '',
            'is_active' => $validated['is_active'],
            'updated_by' => $userId,
        ]);

        if ($request->hasFile('file')) {
            $staff->image = $this->storeLegacyUpload($request->file('file'), 'staff/image');
        }

        if ($request->hasFile('first_doc')) {
            $staff->resume = $this->storeLegacyUpload($request->file('first_doc'), 'staff/documents');
        }

        if ($request->hasFile('second_doc')) {
            $staff->joining_letter = $this->storeLegacyUpload($request->file('second_doc'), 'staff/documents');
        }

        if ($request->hasFile('fourth_doc')) {
            $staff->other_document_file = $this->storeLegacyUpload($request->file('fourth_doc'), 'staff/documents');
        }

        $staff->save();

        DB::table('staff_academic')->where('staff_id', $staffId)->delete();
        DB::table('staff_certifications')->where('staff_id', $staffId)->delete();
        DB::table('staff_experiences')->where('staff_id', $staffId)->delete();
        DB::table('staff_pay')->where('staff_id', $staffId)->delete();

        $this->persistAcademicRows($request, $staffId, (int) $validated['brc_id'], $userId);
        $this->persistCertificationRows($request, $staffId, (int) $validated['brc_id'], $userId);
        $this->persistExperienceRows($request, $staffId, (int) $validated['brc_id'], $userId);
        $this->persistPayrollRows($request, $staffId, (int) $validated['brc_id'], $userId);

        return redirect()
            ->route('admin.hrms.staff.profile', $staffId)
            ->with('success', 'Staff record updated successfully.');
    }

    public function storeOption(Request $request, string $type): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'branch_id' => ['nullable', 'integer', Rule::exists('branch', 'id')],
        ]);

        $name = trim($validated['name']);
        $userId = auth()->id() ?? 0;

        if ($type === 'designation') {
            $designation = Designation::query()->firstOrCreate(
                ['name' => $name],
                ['code' => null, 'note' => '', 'is_active' => 'yes']
            );

            return response()->json([
                'id' => $designation->id,
                'name' => $designation->name,
                'type' => $type,
            ]);
        }

        if ($type === 'department') {
            $department = Department::query()->firstOrCreate(
                ['name' => $name],
                ['code' => null, 'note' => '', 'is_active' => 'yes']
            );

            return response()->json([
                'id' => $department->id,
                'name' => $department->name,
                'type' => $type,
            ]);
        }

        if ($type === 'role') {
            $branchId = (int) ($validated['branch_id'] ?? 0);

            if ($branchId <= 0) {
                return response()->json(['message' => 'Branch is required for role creation.'], 422);
            }

            $role = Role::query()->firstOrCreate(
                ['name' => $name],
                ['is_active' => 1, 'is_system' => 0, 'is_superadmin' => 0]
            );

            $roleBranch = RoleBranch::query()->firstOrCreate(
                ['brc_id' => $branchId, 'roles_id' => $role->id],
                ['is_active' => 1, 'is_system' => 0, 'created_by' => $userId, 'updated_by' => $userId]
            );

            return response()->json([
                'id' => $roleBranch->id,
                'name' => $role->name,
                'type' => $type,
            ]);
        }

        return response()->json(['message' => 'Unsupported option type.'], 422);
    }

    public function appointmentForm(int $staffId, Request $request): View
    {
        $staff = $this->transformStaffRecord(
            $this->staffDirectoryQuery($request, null)->where('staff.id', $staffId)->firstOrFail()
        );

        $body = "This appointment form certifies that {$staff['full_name']} is recorded in the TNT SOL staff directory under the role of {$staff['role_name']} at {$staff['branch_name']}.\n\n"
            ."Employee ID: {$staff['employee_id']}\n"
            ."Department: {$staff['department_name']}\n"
            ."Designation: {$staff['designation_name']}\n"
            ."Date of Joining: {$staff['date_of_joining_label']}";

        return view('admin.hrms.staff.print', [
            'title' => 'Appointment Form',
            'staff' => $staff,
            'body' => $body,
        ]);
    }

    public function serviceExperienceCertificate(int $staffId, Request $request): View
    {
        $staff = $this->transformStaffRecord(
            $this->staffDirectoryQuery($request, null)->where('staff.id', $staffId)->firstOrFail()
        );

        $body = "This service experience certificate confirms that {$staff['full_name']} has served as {$staff['designation_name']} in {$staff['department_name']} at {$staff['branch_name']}.\n\n"
            ."Recorded role: {$staff['role_name']}\n"
            ."Staff ID: {$staff['employee_id']}\n"
            ."Joining date: {$staff['date_of_joining_label']}";

        return view('admin.hrms.staff.print', [
            'title' => 'Service Experience Certificate',
            'staff' => $staff,
            'body' => $body,
        ]);
    }

    private function resolveBranchId(Request $request): ?int
    {
        $branchId = $request->integer('brc_id');

        if ($branchId > 0) {
            return $branchId;
        }

        return $this->branchContext->id()
            ?: Branch::query()->orderBy('id')->value('id');
    }

    private function rolesForBranch(?int $branchId): Collection
    {
        if ($branchId === null) {
            return collect();
        }

        return RoleBranch::query()
            ->with('role:id,name')
            ->forBranch($branchId)
            ->active()
            ->orderBy('id')
            ->get(['id', 'roles_id', 'brc_id'])
            ->map(fn (RoleBranch $roleBranch): array => [
                'id' => $roleBranch->id,
                'name' => trim((string) $roleBranch->role?->name),
            ])
            ->filter(fn (array $role): bool => $role['name'] !== '')
            ->values();
    }

    private function generateEmployeeId(?int $branchId): string
    {
        if ($branchId === null) {
            return 'stf-01';
        }

        $nextCode = ((int) Staff::query()->where('brc_id', $branchId)->count()) + 1;

        return 'stf-'.str_pad((string) $nextCode, 2, '0', STR_PAD_LEFT);
    }

    /**
     * @return array<string, mixed>
     */
    private function staffFormData(?int $branchId): array
    {
        return [
            'branches' => Branch::query()->orderBy('name')->get(['id', 'name']),
            'roles' => $this->rolesForBranch($branchId),
            'departments' => Department::query()->orderBy('name')->get(['id', 'name']),
            'designations' => Designation::query()->orderBy('name')->get(['id', 'name']),
            'genderOptions' => ['Male' => 'Male', 'Female' => 'Female'],
            'maritalStatuses' => ['Single', 'Married', 'Widowed', 'Seperated', 'Not Specified'],
            'contractTypes' => ['permanent' => 'Permanent', 'probation' => 'Probation'],
            'shiftOptions' => ['morning' => 'Morning', 'evening' => 'Evening', 'night' => 'Night'],
            'academicYears' => DB::table('adcademicyear')->orderBy('name')->get(['id', 'name']),
            'universityBoards' => DB::table('universityboard')->orderBy('name')->get(['id', 'name']),
            'degreeCertificates' => DB::table('degreecertificate')->orderBy('name')->get(['id', 'name']),
            'institutes' => DB::table('institute')->orderBy('name')->get(['id', 'name']),
            'trainings' => DB::table('training')->orderBy('name')->get(['id', 'name']),
            'organizations' => DB::table('organization')->orderBy('name')->get(['id', 'name']),
            'payTypes' => DB::table('accountshead')->where('accounts_head_id', 31)->orderBy('name')->get(['id', 'name']),
            'payDeductionTypes' => DB::table('accountshead')->where('accounts_head_id', 32)->orderBy('name')->get(['id', 'name']),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function editableAcademicRows(int $staffId): array
    {
        return DB::table('staff_academic')
            ->where('staff_id', $staffId)
            ->orderBy('id')
            ->get()
            ->map(fn (object $row): array => (array) $row)
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function editableCertificationRows(int $staffId): array
    {
        return DB::table('staff_certifications')
            ->where('staff_id', $staffId)
            ->orderBy('id')
            ->get()
            ->map(fn (object $row): array => (array) $row)
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function editableExperienceRows(int $staffId): array
    {
        return DB::table('staff_experiences')
            ->where('staff_id', $staffId)
            ->orderBy('id')
            ->get()
            ->map(fn (object $row): array => (array) $row)
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function editablePayRows(int $staffId, bool $deductions): array
    {
        return DB::table('staff_pay')
            ->where('staff_id', $staffId)
            ->when($deductions, fn (QueryBuilder $query) => $query->where('amount', '<', 0))
            ->when(! $deductions, fn (QueryBuilder $query) => $query->where('amount', '>=', 0))
            ->orderBy('id')
            ->get()
            ->map(function (object $row) use ($deductions): array {
                $record = (array) $row;

                if ($deductions) {
                    $record['amount'] = abs((float) ($record['amount'] ?? 0));
                }

                return $record;
            })
            ->all();
    }

    private function storeLegacyUpload(?UploadedFile $file, string $directory): string
    {
        if (! $file instanceof UploadedFile) {
            return '';
        }

        $destination = public_path('uploads/'.$directory);

        if (! is_dir($destination)) {
            mkdir($destination, 0777, true);
        }

        $filename = time().'_'.bin2hex(random_bytes(4)).'.'.$file->getClientOriginalExtension();
        $file->move($destination, $filename);

        return $filename;
    }

    private function persistAcademicRows(Request $request, int $staffId, int $branchId, int $userId): void
    {
        $institutes = $request->input('eduinst', []);

        foreach ($institutes as $index => $instituteId) {
            if (! $this->hasRowData([$instituteId, $request->input("edudegree.$index"), $request->input("edufrom.$index"), $request->input("eduto.$index"), $request->input("edumaxmark.$index"), $request->input("eduobtmark.$index"), $request->input("edugrd.$index")])) {
                continue;
            }

            DB::table('staff_academic')->insert([
                'brc_id' => $branchId,
                'staff_id' => $staffId,
                'ints_id' => $instituteId ?: null,
                'from' => $request->input("edufrom.$index") ?: null,
                'to' => $request->input("eduto.$index") ?: null,
                'degree_id' => $request->input("edudegree.$index") ?: null,
                'maxmarks' => $request->input("edumaxmark.$index") ?: null,
                'obtmarks' => $request->input("eduobtmark.$index") ?: null,
                'grade' => $request->input("edugrd.$index") ?: null,
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);
        }
    }

    private function persistCertificationRows(Request $request, int $staffId, int $branchId, int $userId): void
    {
        $institutes = $request->input('cerinst', []);

        foreach ($institutes as $index => $instituteId) {
            if (! $this->hasRowData([$instituteId, $request->input("certrining.$index"), $request->input("cerfrom.$index"), $request->input("certo.$index"), $request->input("cerobtmark.$index"), $request->input("cergrd.$index")])) {
                continue;
            }

            DB::table('staff_certifications')->insert([
                'brc_id' => $branchId,
                'staff_id' => $staffId,
                'inst_id' => $instituteId ?: null,
                'from' => $request->input("cerfrom.$index") ?: null,
                'to' => $request->input("certo.$index") ?: null,
                'trining_id' => $request->input("certrining.$index") ?: null,
                'obtmarks' => $request->input("cerobtmark.$index") ?: null,
                'grade' => $request->input("cergrd.$index") ?: null,
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);
        }
    }

    private function persistExperienceRows(Request $request, int $staffId, int $branchId, int $userId): void
    {
        $organizations = $request->input('exporg', []);

        foreach ($organizations as $index => $organizationId) {
            if (! $this->hasRowData([$organizationId, $request->input("exppost.$index"), $request->input("expcontact.$index"), $request->input("expfrom.$index"), $request->input("expto.$index"), $request->input("expsalary.$index"), $request->input("explereason.$index")])) {
                continue;
            }

            DB::table('staff_experiences')->insert([
                'brc_id' => $branchId,
                'staff_id' => $staffId,
                'org_id' => $organizationId ?: null,
                'from' => $request->input("expfrom.$index") ?: null,
                'to' => $request->input("expto.$index") ?: null,
                'postion_id' => $request->input("exppost.$index") ?: null,
                'contactno' => $request->input("expcontact.$index") ?: null,
                'salary' => $request->input("expsalary.$index") ?: null,
                'reason' => $request->input("explereason.$index") ?: null,
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);
        }
    }

    private function persistPayrollRows(Request $request, int $staffId, int $branchId, int $userId): void
    {
        foreach ((array) $request->input('salary_type', []) as $index => $typeId) {
            $amount = $request->input("salary_amount.$index");

            if (! $this->hasRowData([$typeId, $amount])) {
                continue;
            }

            DB::table('staff_pay')->insert([
                'brc_id' => $branchId,
                'staff_id' => $staffId,
                'type_id' => $typeId ?: null,
                'frequency' => $request->input("frequency.$index") ?: null,
                'amount' => $amount ?: null,
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);
        }

        foreach ((array) $request->input('salary_ded_type', []) as $index => $typeId) {
            $amount = $request->input("salary_ded_amount.$index");

            if (! $this->hasRowData([$typeId, $amount])) {
                continue;
            }

            DB::table('staff_pay')->insert([
                'brc_id' => $branchId,
                'staff_id' => $staffId,
                'type_id' => $typeId ?: null,
                'frequency' => 'Deduction',
                'amount' => $amount ? (0 - (float) $amount) : null,
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);
        }
    }

    private function hasRowData(array $values): bool
    {
        foreach ($values as $value) {
            if (trim((string) $value) !== '') {
                return true;
            }
        }

        return false;
    }

    private function staffDirectoryQuery(Request $request, ?int $branchId): Builder
    {
        $searchField = $request->string('selected_value_staff')->toString() ?: 'staff_id';
        $searchText = trim($request->string('text_staff')->toString());

        return Staff::query()
            ->selectRaw('
                staff.*,
                branch.name as branch_name,
                designation.name as designation_name,
                department.name as department_name,
                roles.name as role_name
            ')
            ->leftJoin('branch', 'branch.id', '=', 'staff.brc_id')
            ->leftJoin('designation', 'designation.id', '=', 'staff.designation')
            ->leftJoin('department', 'department.id', '=', 'staff.department')
            ->leftJoin('roles_branch', 'roles_branch.id', '=', 'staff.role_id')
            ->leftJoin('roles', 'roles.id', '=', 'roles_branch.roles_id')
            ->where('staff.is_active', 1)
            ->when($branchId !== null, fn (Builder $query) => $query->where('staff.brc_id', $branchId))
            ->when($request->filled('role'), fn (Builder $query) => $query->where('staff.role_id', $request->integer('role')))
            ->when($searchText !== '', function (Builder $query) use ($searchField, $searchText): void {
                if ($searchField === 'name') {
                    $query->where(function (Builder $nestedQuery) use ($searchText): void {
                        $nestedQuery->where('staff.name', 'like', "%{$searchText}%")
                            ->orWhere('staff.surname', 'like', "%{$searchText}%");
                    });

                    return;
                }

                if ($searchField === 'role') {
                    $query->where('roles.name', 'like', "%{$searchText}%");

                    return;
                }

                $query->where('staff.employee_id', $searchText);
            })
            ->orderBy('staff.id');
    }

    /**
     * @return array<string, mixed>
     */
    private function transformStaffRecord(object $record): array
    {
        $fullName = trim(implode(' ', array_filter([(string) $record->name, (string) ($record->surname ?? '')])));
        $category = match ((int) ($record->category ?? 0)) {
            1 => 'Administration',
            2 => 'Teaching',
            3 => 'Allied',
            default => '-',
        };

        return [
            'id' => (int) $record->id,
            'branch_id' => (int) $record->brc_id,
            'employee_id' => (string) ($record->employee_id ?: '-'),
            'full_name' => $fullName !== '' ? $fullName : '-',
            'father_name' => (string) (($record->father_name ?? '') !== '' ? $record->father_name : '-'),
            'role_name' => trim((string) ($record->role_name ?? '')) ?: '-',
            'branch_name' => (string) (($record->branch_name ?? '') !== '' ? $record->branch_name : '-'),
            'department_name' => (string) (($record->department_name ?? '') !== '' ? $record->department_name : '-'),
            'designation_name' => (string) (($record->designation_name ?? '') !== '' ? $record->designation_name : '-'),
            'category_name' => $category,
            'mobile_no' => (string) (($record->whatsapp_no ?? $record->contact_no ?? '') !== '' ? ($record->whatsapp_no ?? $record->contact_no) : '-'),
            'status_label' => (int) ($record->is_active ?? 0) === 1 ? 'Active' : 'Inactive',
            'dob_label' => $this->formatDateValue($record->dob ?? null),
            'date_of_joining_label' => $this->formatDateValue($record->date_of_joining ?? null),
            'date_of_leaving_label' => $this->formatDateValue($record->date_of_leaving ?? null),
            'disable_at_label' => $this->formatDateValue($record->disable_at ?? null),
            'contact_no' => (string) (($record->contact_no ?? '') !== '' ? $record->contact_no : '-'),
            'whatsapp_no' => (string) (($record->whatsapp_no ?? '') !== '' ? $record->whatsapp_no : '-'),
            'emergency_contact_no' => (string) (($record->emergency_contact_no ?? '') !== '' ? $record->emergency_contact_no : '-'),
            'email' => (string) (($record->email ?? '') !== '' ? $record->email : '-'),
            'local_address' => (string) (($record->local_address ?? '') !== '' ? $record->local_address : '-'),
            'permanent_address' => (string) (($record->permanent_address ?? '') !== '' ? $record->permanent_address : '-'),
            'username' => (string) (($record->email ?? '') !== '' ? $record->email : '-'),
            'plain_password' => (string) (($record->ch_password ?? '') !== '' ? $record->ch_password : '-'),
            'cnic' => (string) (($record->cnic ?? '') !== '' ? $record->cnic : '-'),
            'gender' => (string) (($record->gender ?? '') !== '' ? $record->gender : '-'),
            'marital_status' => (string) (($record->marital_status ?? '') !== '' ? $record->marital_status : '-'),
            'note' => (string) (($record->note ?? '') !== '' ? $record->note : '-'),
            'contract_type' => (string) (($record->contract_type ?? '') !== '' ? $record->contract_type : '-'),
            'shift' => (string) (($record->shift ?? '') !== '' ? $record->shift : '-'),
            'location' => (string) (($record->location ?? '') !== '' ? $record->location : '-'),
            'total_security' => (string) (($record->total_security ?? '') !== '' ? $record->total_security : '-'),
            'month_security' => (string) (($record->month_security ?? '') !== '' ? $record->month_security : '-'),
            'account_title' => (string) (($record->account_title ?? '') !== '' ? $record->account_title : '-'),
            'bank_name' => (string) (($record->bank_name ?? '') !== '' ? $record->bank_name : '-'),
            'bank_branch' => (string) (($record->bank_branch ?? '') !== '' ? $record->bank_branch : '-'),
            'bank_account_no' => (string) (($record->bank_account_no ?? '') !== '' ? $record->bank_account_no : '-'),
            'iban_code' => (string) (($record->IBAN_code ?? '') !== '' ? $record->IBAN_code : '-'),
            'facebook' => (string) (($record->facebook ?? '') !== '' ? $record->facebook : '-'),
            'twitter' => (string) (($record->twitter ?? '') !== '' ? $record->twitter : '-'),
            'linkedin' => (string) (($record->linkedin ?? '') !== '' ? $record->linkedin : '-'),
            'instagram' => (string) (($record->instagram ?? '') !== '' ? $record->instagram : '-'),
        ];
    }

    private function formatDateValue(mixed $value): string
    {
        $stringValue = trim((string) $value);

        if ($stringValue === '' || $stringValue === '0000-00-00') {
            return '-';
        }

        try {
            return Carbon::parse($stringValue)->format('d/m/Y');
        } catch (\Throwable) {
            return '-';
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function academicRecords(int $staffId): array
    {
        return DB::table('staff_academic as sa')
            ->leftJoin('universityboard as ub', 'ub.id', '=', 'sa.ints_id')
            ->leftJoin('degreecertificate as dc', 'dc.id', '=', 'sa.degree_id')
            ->leftJoin('adcademicyear as yf', 'yf.id', '=', 'sa.from')
            ->leftJoin('adcademicyear as yt', 'yt.id', '=', 'sa.to')
            ->where('sa.staff_id', $staffId)
            ->orderBy('sa.id')
            ->get([
                'sa.id',
                'ub.name as institute_name',
                'yf.name as from_year',
                'yt.name as to_year',
                'dc.name as degree_name',
                'sa.maxmarks',
                'sa.obtmarks',
                'sa.grade',
            ])
            ->map(fn (object $record): array => [
                'id' => (int) $record->id,
                'institute_name' => $this->dashValue($record->institute_name),
                'from_year' => $this->dashValue($record->from_year),
                'to_year' => $this->dashValue($record->to_year),
                'degree_name' => $this->dashValue($record->degree_name),
                'maxmarks' => $this->dashNumber($record->maxmarks),
                'obtmarks' => $this->dashNumber($record->obtmarks),
                'grade' => $this->dashValue($record->grade),
            ])
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function certificationRecords(int $staffId): array
    {
        return DB::table('staff_certifications as sc')
            ->leftJoin('institute as i', 'i.id', '=', 'sc.inst_id')
            ->leftJoin('training as t', 't.id', '=', 'sc.trining_id')
            ->leftJoin('adcademicyear as yf', 'yf.id', '=', 'sc.from')
            ->leftJoin('adcademicyear as yt', 'yt.id', '=', 'sc.to')
            ->where('sc.staff_id', $staffId)
            ->orderBy('sc.id')
            ->get([
                'sc.id',
                'i.name as institute_name',
                't.name as training_name',
                'yf.name as from_year',
                'yt.name as to_year',
                'sc.obtmarks',
                'sc.grade',
            ])
            ->map(fn (object $record): array => [
                'id' => (int) $record->id,
                'institute_name' => $this->dashValue($record->institute_name),
                'training_name' => $this->dashValue($record->training_name),
                'from_year' => $this->dashValue($record->from_year),
                'to_year' => $this->dashValue($record->to_year),
                'obtmarks' => $this->dashNumber($record->obtmarks),
                'grade' => $this->dashValue($record->grade),
            ])
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function experienceRecords(int $staffId): array
    {
        return DB::table('staff_experiences as se')
            ->leftJoin('organization as o', 'o.id', '=', 'se.org_id')
            ->leftJoin('designation as d', 'd.id', '=', 'se.postion_id')
            ->leftJoin('adcademicyear as yf', 'yf.id', '=', 'se.from')
            ->leftJoin('adcademicyear as yt', 'yt.id', '=', 'se.to')
            ->where('se.staff_id', $staffId)
            ->orderBy('se.id')
            ->get([
                'se.id',
                'o.name as organization_name',
                'd.name as position_name',
                'se.contactno',
                'yf.name as from_year',
                'yt.name as to_year',
                'se.salary',
                'se.reason',
            ])
            ->map(fn (object $record): array => [
                'id' => (int) $record->id,
                'organization_name' => $this->dashValue($record->organization_name),
                'position_name' => $this->dashValue($record->position_name),
                'contactno' => $this->dashValue($record->contactno),
                'from_year' => $this->dashValue($record->from_year),
                'to_year' => $this->dashValue($record->to_year),
                'salary' => $this->dashNumber($record->salary),
                'reason' => $this->dashValue($record->reason),
            ])
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function payRecords(int $staffId): array
    {
        return DB::table('staff_pay as sp')
            ->leftJoin('accountshead as ah', 'ah.id', '=', 'sp.type_id')
            ->where('sp.staff_id', $staffId)
            ->orderBy('sp.id')
            ->get([
                'sp.id',
                'ah.name as type_name',
                'sp.frequency',
                'sp.amount',
            ])
            ->map(fn (object $record): array => [
                'id' => (int) $record->id,
                'type_name' => $this->dashValue($record->type_name),
                'frequency' => $this->dashValue($record->frequency),
                'amount' => (float) ($record->amount ?? 0),
            ])
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function leaveRecords(int $staffId): array
    {
        return DB::table('staff_leave_request as slr')
            ->leftJoin('leave_types as lt', 'lt.id', '=', 'slr.leave_type_id')
            ->where('slr.staff_id', $staffId)
            ->orderByDesc('slr.id')
            ->get([
                'slr.id',
                'lt.name as leave_type_name',
                'slr.leave_from',
                'slr.leave_to',
                'slr.leave_days',
                'slr.status',
                'slr.employee_remark',
            ])
            ->map(fn (object $record): array => [
                'id' => (int) $record->id,
                'leave_type_name' => $this->dashValue($record->leave_type_name),
                'leave_from' => $this->formatDateValue($record->leave_from),
                'leave_to' => $this->formatDateValue($record->leave_to),
                'leave_days' => $this->dashNumber($record->leave_days),
                'status' => $this->dashValue($record->status),
                'employee_remark' => $this->dashValue($record->employee_remark),
            ])
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function documentRecords(object $staff): array
    {
        $documents = [
            [
                'label' => 'Resume',
                'filename' => $this->dashValue($staff->resume ?? null),
                'type' => 'resume',
            ],
            [
                'label' => 'Joining Letter',
                'filename' => $this->dashValue($staff->joining_letter ?? null),
                'type' => 'joining',
            ],
            [
                'label' => 'Other Document',
                'filename' => $this->dashValue($staff->other_document_file ?? null),
                'type' => 'other',
            ],
        ];

        return array_values(array_filter($documents, fn (array $document): bool => $document['filename'] !== '-'));
    }

    private function dashValue(mixed $value): string
    {
        $stringValue = trim((string) $value);

        return $stringValue !== '' ? $stringValue : '-';
    }

    private function dashNumber(mixed $value): string
    {
        if ($value === null) {
            return '-';
        }

        $number = (float) $value;

        return $number !== 0.0 ? rtrim(rtrim((string) $number, '0'), '.') : '-';
    }
}
