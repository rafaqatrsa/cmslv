<?php

namespace App\Http\Controllers\Admin\Adm;

use App\Http\Requests\Admin\Adm\StudentRegistrationRequest;
use App\Models\Account\AccountHead;
use App\Models\Adm\Enquiry as AdmEnquiry;
use App\Models\Adm\StudentRegistration;
use App\Models\Adm\StudentRegistrationFee;
use App\Models\Branch;
use App\Models\Setting;
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

class StudentRegistrationController extends BaseAdmController
{
    public function index(Request $request): View
    {
        return $this->formView($request);
    }

    public function create(Request $request): View
    {
        return $this->formView($request);
    }

    public function enquiryDetail(Request $request): JsonResponse
    {
        $branchId = $this->resolvedBranchId($request);
        $enquiryId = $request->integer('enquiry_id');
        $kidId = $request->integer('enquiry_kid_id');

        if (! $enquiryId || ! $kidId || ! Schema::hasTable('enquiry_kid')) {
            return response()->json([]);
        }

        $query = DB::table('enquiry')
            ->join('enquiry_kid', function ($join) use ($kidId): void {
                $join->on('enquiry_kid.enquiry_id', '=', 'enquiry.id')
                    ->where('enquiry_kid.id', '=', $kidId);
            })
            ->leftJoin('classes', 'classes.id', '=', 'enquiry_kid.class_id')
            ->leftJoin('occupation', 'occupation.id', '=', 'enquiry.occupation_id')
            ->where('enquiry.id', $enquiryId)
            ->where('enquiry.status', 'won')
            ->select([
                'enquiry.id as enquiry_id',
                'enquiry.brc_id',
                'enquiry.name',
                'enquiry.contact',
                'enquiry.id_card',
                'enquiry.email',
                'enquiry.father_name',
                'enquiry.occupation_id',
                'occupation.name as occupation_name',
                'enquiry.address',
                'enquiry_kid.id as enquiry_kid_id',
                'enquiry_kid.kid_name',
                'enquiry_kid.class_id',
                'classes.class',
            ]);

        if ($branchId) {
            $query->where('enquiry.brc_id', $branchId);
        }

        $detail = $query->first();

        if (! $detail) {
            return response()->json([]);
        }

        return response()->json([
            'enquiry_id' => $detail->enquiry_id,
            'enquiry_kid_id' => $detail->enquiry_kid_id,
            'kid_name' => $detail->kid_name,
            'class_id' => $detail->class_id,
            'name' => $detail->name,
            'father_name' => $detail->father_name ?: $detail->name,
            'contact' => $detail->contact,
            'occupation_id' => $detail->occupation_id,
            'id_card' => $detail->id_card,
            'email' => $detail->email,
            'address' => $detail->address,
        ]);
    }

    public function locationOptions(Request $request, string $location): JsonResponse
    {
        $definitions = [
            'provinces' => ['table' => 'province', 'columns' => ['id', 'name'], 'filters' => ['country_id']],
            'divisions' => ['table' => 'division', 'columns' => ['id', 'name'], 'filters' => ['country_id', 'province_id']],
            'districts' => ['table' => 'district', 'columns' => ['id', 'name'], 'filters' => ['country_id', 'province_id', 'division_id']],
            'tehsils' => ['table' => 'tehsils', 'columns' => ['id', 'name'], 'filters' => ['country_id', 'province_id', 'division_id', 'district_id']],
            'areas' => ['table' => 'area', 'columns' => ['id', 'name'], 'filters' => ['country_id', 'province_id', 'division_id', 'district_id', 'tehsils_id']],
        ];

        abort_unless(isset($definitions[$location]), 404);

        $definition = $definitions[$location];
        $query = DB::table($definition['table'])->where('is_active', 'yes');

        foreach ($definition['filters'] as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, $request->integer($filter));
            }
        }

        return response()->json($query->orderBy('name')->get($definition['columns']));
    }

    public function store(StudentRegistrationRequest $request): RedirectResponse
    {
        $registration = DB::transaction(function () use ($request): StudentRegistration {
            $branchId = $this->resolvedBranchId($request);
            $payload = $this->registrationPayload($request, $branchId);
            $payload['regd_no'] ??= $this->nextRegistrationNumber($branchId);
            $payload['username'] = $payload['username'] ?: null;
            $payload['password'] = $payload['password'] ?: null;

            $photo = $request->file('image') ?? $request->file('student_photo');

            if ($photo) {
                $payload['image'] = $photo->storePublicly('admission/student-registrations', 'public');
            }

            /** @var StudentRegistration $registration */
            $registration = StudentRegistration::query()->create($payload);

            $registration->forceFill([
                'username' => $payload['username'] ?: 'regstd'.$registration->id,
                'password' => $payload['password'] ?: $this->generatedPassword(),
            ])->save();

            $this->syncFees($registration, $request->input('fee_rows', []), $branchId, $request);
            $this->syncRegistrationEnquiryStatus($registration);

            return $registration;
        });

        return redirect()
            ->route('admin.adm.student-registrations.show', $registration)
            ->with('status', 'Student registration created successfully.');
    }

    public function show(StudentRegistration $studentRegistration, Request $request): View
    {
        $studentRegistration->load('fees');

        return view('admin.adm.student-registrations.show', $this->viewData($request, $studentRegistration));
    }

    public function edit(StudentRegistration $studentRegistration, Request $request): View
    {
        $studentRegistration->load('fees');

        return $this->formView($request, $studentRegistration, true);
    }

    public function update(StudentRegistrationRequest $request, StudentRegistration $studentRegistration): RedirectResponse
    {
        DB::transaction(function () use ($request, $studentRegistration): void {
            $branchId = $this->resolvedBranchId($request, $studentRegistration);
            $payload = $this->registrationPayload($request, $branchId);
            $payload['regd_no'] ??= $studentRegistration->regd_no ?: $this->nextRegistrationNumber($branchId);

            $photo = $request->file('image') ?? $request->file('student_photo');

            if ($photo) {
                if ($studentRegistration->image) {
                    Storage::disk('public')->delete($studentRegistration->image);
                }

                $payload['image'] = $photo->storePublicly('admission/student-registrations', 'public');
            }

            $studentRegistration->fill($payload)->save();

            $this->syncFees($studentRegistration, $request->input('fee_rows', []), $branchId, $request);
            $this->syncRegistrationEnquiryStatus($studentRegistration);
        });

        return redirect()
            ->route('admin.adm.student-registrations.show', $studentRegistration)
            ->with('status', 'Student registration updated successfully.');
    }

    public function destroy(StudentRegistration $studentRegistration): RedirectResponse
    {
        DB::transaction(function () use ($studentRegistration): void {
            $studentRegistration->fees()->delete();

            if ($studentRegistration->image) {
                Storage::disk('public')->delete($studentRegistration->image);
            }

            $studentRegistration->delete();
        });

        return redirect()
            ->route('admin.adm.student-registrations.index')
            ->with('status', 'Student registration deleted successfully.');
    }

    private function formView(Request $request, ?StudentRegistration $registration = null, bool $editing = false): View
    {
        if ($registration) {
            $registration->load('fees');
        }

        return view('admin.adm.student-registrations.index', $this->viewData($request, $registration, $editing));
    }

    /**
     * @return array<string, mixed>
     */
    private function viewData(Request $request, ?StudentRegistration $registration = null, bool $editing = false): array
    {
        $branchId = $this->resolvedBranchId($request, $registration);
        $search = $request->string('search')->toString();
        $registrationTable = (new StudentRegistration)->getTable();
        $sessionList = $this->collectionIfTableExists('sessions', fn () => DB::table('sessions')->orderBy('session')->get(['id', 'session']));
        $academicYearList = $this->collectionIfTableExists('adcademicyear', fn () => DB::table('adcademicyear')->orderBy('name')->get(['id', 'name']));
        $selectedSessionId = $this->currentSessionId($sessionList, $registration?->session_id);
        $selectedAcademicYearId = $this->currentAcademicYearId($academicYearList, $registration?->adcademicyear_id);
        $enquiryDropdownList = $this->enquiryDropdownList($branchId);

        return [
            'registration' => $registration,
            'editing' => $editing,
            'branchId' => $branchId,
            'branches' => $this->collectionIfTableExists((new Branch)->getTable(), fn () => Branch::query()->active()->orderBy('name')->get(['id', 'name'])),
            'classes' => $this->collectionIfTableExists('classes', fn () => DB::table('classes')->orderBy('class')->get(['id', 'class'])),
            'sessions' => $sessionList,
            'academicYears' => $academicYearList,
            'religions' => $this->collectionIfTableExists('religion', fn () => DB::table('religion')->where('is_active', 'yes')->orderBy('name')->get(['id', 'name'])),
            'mediums' => $this->collectionIfTableExists('medium', fn () => DB::table('medium')->where('is_active', 'yes')->orderBy('name')->get(['id', 'name'])),
            'previousSchools' => $this->collectionIfTableExists('perviousschool', fn () => DB::table('perviousschool')->where('is_active', 'yes')->orderBy('name')->get(['id', 'name'])),
            'occupations' => $this->collectionIfTableExists('occupation', fn () => DB::table('occupation')->where('is_active', 'yes')->orderBy('name')->get(['id', 'name'])),
            'countries' => $this->collectionIfTableExists('country', fn () => DB::table('country')->orderBy('name')->get(['id', 'name'])),
            'provinces' => $this->collectionIfTableExists('province', fn () => DB::table('province')->where('is_active', 'yes')->orderBy('name')->get(['id', 'name'])),
            'divisions' => $this->collectionIfTableExists('division', fn () => DB::table('division')->where('is_active', 'yes')->orderBy('name')->get(['id', 'name'])),
            'districts' => $this->collectionIfTableExists('district', fn () => DB::table('district')->where('is_active', 'yes')->orderBy('name')->get(['id', 'name'])),
            'tehsils' => $this->collectionIfTableExists('tehsils', fn () => DB::table('tehsils')->where('is_active', 'yes')->orderBy('name')->get(['id', 'name'])),
            'areas' => $this->collectionIfTableExists('area', fn () => DB::table('area')->where('is_active', 'yes')->orderBy('name')->get(['id', 'name'])),
            'feeHeads' => $this->collectionIfTableExists((new AccountHead)->getTable(), fn () => AccountHead::query()->where('is_active', 'yes')->orderBy('name')->get(['id', 'name'])),
            'registrations' => $this->registrationPaginator($branchId, $search, $request, $registrationTable),
            'studentRegdList' => $this->registrationPaginator($branchId, $search, $request, $registrationTable),
            'enquiryDropdownList' => $enquiryDropdownList,
            'sessionlist' => $sessionList,
            'classlist' => $this->collectionIfTableExists('classes', fn () => DB::table('classes')->orderBy('class')->get(['id', 'class'])),
            'adcademicyearlist' => $academicYearList,
            'religionlist' => $this->collectionIfTableExists('religion', fn () => DB::table('religion')->where('is_active', 'yes')->orderBy('name')->get(['id', 'name'])),
            'mediumlist' => $this->collectionIfTableExists('medium', fn () => DB::table('medium')->where('is_active', 'yes')->orderBy('name')->get(['id', 'name'])),
            'perviousschoollist' => $this->collectionIfTableExists('perviousschool', fn () => DB::table('perviousschool')->where('is_active', 'yes')->orderBy('name')->get(['id', 'name'])),
            'occuptionlist' => $this->collectionIfTableExists('occupation', fn () => DB::table('occupation')->where('is_active', 'yes')->orderBy('name')->get(['id', 'name'])),
            'countrylist' => $this->collectionIfTableExists('country', fn () => DB::table('country')->orderBy('name')->get(['id', 'name', 'telephonePrefix'])),
            'feeRows' => $this->feeRowsForView($registration),
            'generatedRegdNo' => $registration?->regd_no ?? $this->nextRegistrationNumber($branchId),
            'regd_no' => $registration?->regd_no ?? $this->nextRegistrationNumber($branchId),
            'current_session' => $selectedSessionId,
            'current_academic_year' => $selectedAcademicYearId,
            'genders' => $this->genders(),
            'schoolDate' => now()->toDateString(),
        ];
    }

    /**
     * @return array<int, array{value: string, label: string}>
     */
    private function genders(): array
    {
        return [
            ['value' => 'Male', 'label' => 'Male'],
            ['value' => 'Female', 'label' => 'Female'],
            ['value' => 'Other', 'label' => 'Other'],
        ];
    }

    /**
     * @param  Collection<int, object>  $sessions
     */
    private function currentSessionId(Collection $sessions, mixed $fallback = null): ?int
    {
        if (is_numeric($fallback) && (int) $fallback > 0) {
            return (int) $fallback;
        }

        $currentYear = (int) now()->format('Y');

        $matched = $sessions->first(function (object $session) use ($currentYear): bool {
            return str_starts_with((string) data_get($session, 'session', ''), (string) $currentYear);
        });

        if ($matched) {
            return (int) data_get($matched, 'id');
        }

        return $sessions->last()?->id ? (int) $sessions->last()->id : null;
    }

    /**
     * @param  Collection<int, object>  $academicYears
     */
    private function currentAcademicYearId(Collection $academicYears, mixed $fallback = null): ?int
    {
        if (is_numeric($fallback) && (int) $fallback > 0) {
            return (int) $fallback;
        }

        $currentYear = now()->format('Y');

        $matched = $academicYears->first(function (object $academicYear) use ($currentYear): bool {
            return trim((string) data_get($academicYear, 'name', '')) === $currentYear;
        });

        if ($matched) {
            return (int) data_get($matched, 'id');
        }

        return $academicYears->last()?->id ? (int) $academicYears->last()->id : null;
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    private function enquiryDropdownList(?int $branchId): Collection
    {
        $table = (new AdmEnquiry)->getTable();
        $kidTable = 'enquiry_kid';

        if (! Schema::hasTable($table) || ! Schema::hasTable($kidTable)) {
            return collect();
        }

        $query = AdmEnquiry::query()
            ->join($kidTable.' as enquiry_kid', 'enquiry_kid.enquiry_id', '=', $table.'.id')
            ->leftJoin('classes', 'classes.id', '=', 'enquiry_kid.class_id')
            ->whereRaw('LOWER('.$table.'.status) = ?', ['won'])
            ->whereNotExists(function ($subquery): void {
                $subquery->selectRaw('1')
                    ->from((new StudentRegistration)->getTable().' as registered_students')
                    ->whereColumn('registered_students.registration_enquiry_kid_id', 'enquiry_kid.id');
            })
            ->latest($table.'.id')
            ->limit(200)
            ->select([
                $table.'.id as enquiry_id',
                $table.'.enquiry_no',
                $table.'.name',
                $table.'.father_name',
                $table.'.contact',
                $table.'.phone',
                $table.'.status',
                'enquiry_kid.id as enquiry_kid_id',
                'enquiry_kid.kid_name',
                'enquiry_kid.class_id',
                'classes.class',
            ]);

        if ($branchId && Schema::hasColumn($table, 'brc_id')) {
            $query->where($table.'.brc_id', $branchId);
        }

        return $query->get()->map(function (object $enquiry): array {
            return [
                'enquiry_id' => $enquiry->enquiry_id,
                'enquiry_kid_id' => $enquiry->enquiry_kid_id,
                'enquiry_no' => $enquiry->enquiry_no ?: 'ENQ-'.$enquiry->enquiry_id,
                'kid_name' => $enquiry->kid_name ?: 'N/A',
                'class_id' => $enquiry->class_id,
                'class' => $enquiry->class,
                'name' => $enquiry->name ?: 'N/A',
                'father_name' => $enquiry->father_name ?: 'N/A',
                'contact' => $enquiry->contact ?: $enquiry->phone ?: 'N/A',
                'status' => $enquiry->status ?: 'Active',
            ];
        });
    }

    private function resolvedBranchId(Request $request, ?StudentRegistration $registration = null): int
    {
        if ($registration?->brc_id) {
            return (int) $registration->brc_id;
        }

        $contextId = app(BranchContext::class)->id();

        if ($contextId) {
            return $contextId;
        }

        $requestBranchId = $request->integer('brc_id');

        if ($requestBranchId) {
            return $requestBranchId;
        }

        if (! Schema::hasTable((new Branch)->getTable())) {
            return 0;
        }

        return (int) (Branch::query()->active()->value('id') ?? 0);
    }

    /**
     * @return array<string, mixed>
     */
    private function registrationPayload(StudentRegistrationRequest $request, int $branchId): array
    {
        $validated = $request->validated();
        $regdDate = Carbon::parse($validated['regd_date']);
        $firstName = trim((string) $validated['firstname']);
        $guardianRelation = $this->normalizeGuardianRelation($validated['guardian_relation'] ?? '');

        return [
            'brc_id' => $branchId ?: null,
            'regd_no' => $validated['regd_no'] ?? null,
            'class_id' => $validated['class_id'],
            'session_id' => $validated['session_id'],
            'adcademicyear_id' => $validated['adcademicyear_id'] ?? null,
            'regd_date' => $regdDate->toDateString(),
            'firstname' => $firstName,
            'lastname' => $validated['lastname'] ?? null,
            'mobile_country_code' => $this->normalizeCountryCode($validated['mobile_country_code'] ?? null),
            'mobileno' => $validated['mobileno'] ?? null,
            'dob' => isset($validated['dob']) ? Carbon::parse($validated['dob'])->toDateString() : null,
            'gender' => $validated['gender'],
            'religion' => $validated['religion'] ?? null,
            'medium_id' => $validated['medium_id'] ?? null,
            'previous_school_id' => $validated['previous_school_id'] ?? null,
            'previous_class' => $validated['previous_class'] ?? null,
            'pervious_schl_leaving_date' => isset($validated['pervious_schl_leaving_date']) ? Carbon::parse($validated['pervious_schl_leaving_date'])->toDateString() : null,
            'bayformno' => $validated['bayformno'] ?? null,
            'district_id' => $validated['district_id'] ?? null,
            'tehsils_id' => $validated['tehsils_id'] ?? null,
            'area_id' => $validated['area_id'] ?? null,
            'father_name' => $validated['father_name'] ?? null,
            'father_country_code' => $this->normalizeCountryCode($validated['father_country_code'] ?? null),
            'father_phone' => $validated['father_phone'] ?? null,
            'father_occupation' => $validated['father_occupation'] ?? null,
            'mother_name' => $validated['mother_name'] ?? null,
            'mother_country_code' => $this->normalizeCountryCode($validated['mother_country_code'] ?? null),
            'mother_phone' => $validated['mother_phone'] ?? null,
            'mother_occupation' => $validated['mother_occupation'] ?? null,
            'father_cnic' => $validated['father_cnic'] ?? null,
            'guardian_is' => $validated['guardian_is'] ?? null,
            'guardian_name' => $validated['guardian_name'] ?? null,
            'guardian_relation' => $guardianRelation,
            'guardian_phone' => $validated['guardian_phone'] ?? null,
            'guardian_country_code' => $this->normalizeCountryCode($validated['guardian_country_code'] ?? null),
            'guardian_occupation' => $validated['guardian_occupation'] ?? null,
            'guardian_email' => $validated['guardian_email'] ?? null,
            'address' => $validated['address'] ?? null,
            'issue_date' => $validated['issue_date'] ?? $regdDate->toDateString(),
            'due_date' => $validated['due_date'] ?? $regdDate->copy()->addDays(10)->toDateString(),
            'regd_date_current' => $validated['regd_date_current'] ?? now()->toDateString(),
            'is_active' => $validated['is_active'] ?? 'no',
            'regd_status' => $validated['regd_status'] ?? 1,
            'username' => $validated['username'] ?? null,
            'password' => $validated['password'] ?? null,
            'registration_enquiry_id' => $validated['registration_enquiry_id'] ?? null,
            'registration_enquiry_kid_id' => $validated['registration_enquiry_kid_id'] ?? null,
        ];
    }

    /**
     * @param  array<int, mixed>  $feeRows
     */
    private function syncFees(StudentRegistration $registration, array $feeRows, int $branchId, Request $request): void
    {
        $registration->fees()->delete();

        foreach ($feeRows as $row) {
            if (! is_array($row)) {
                continue;
            }

            $feeTypeId = (int) Arr::get($row, 'feetype_id', 0);
            $amount = Arr::get($row, 'amount');

            if (! $feeTypeId && ($amount === null || $amount === '')) {
                continue;
            }

            StudentRegistrationFee::query()->create([
                'student_regd_id' => $registration->id,
                'brc_id' => $branchId ?: $registration->brc_id,
                'class_id' => $registration->class_id,
                'session_id' => $registration->session_id,
                'feetype_id' => $feeTypeId ?: null,
                'frequency' => Arr::get($row, 'frequency'),
                'amount' => $amount !== null && $amount !== '' ? (float) $amount : 0,
                'date' => $registration->regd_date?->toDateString() ?? $request->date('regd_date')->toDateString(),
                'note' => Arr::get($row, 'note'),
                'is_active' => 'yes',
            ]);
        }
    }

    private function syncRegistrationEnquiryStatus(StudentRegistration $registration): void
    {
        if (! $registration->registration_enquiry_id || ! Schema::hasTable((new AdmEnquiry)->getTable())) {
            return;
        }

        $remainingKids = DB::table('enquiry_kid')
            ->where('enquiry_id', $registration->registration_enquiry_id)
            ->whereNotExists(function ($query): void {
                $query->selectRaw('1')
                    ->from((new StudentRegistration)->getTable().' as registered_students')
                    ->whereColumn('registered_students.registration_enquiry_kid_id', 'enquiry_kid.id');
            })
            ->count();

        if ($remainingKids === 0) {
            AdmEnquiry::query()
                ->whereKey($registration->registration_enquiry_id)
                ->when($registration->brc_id, fn ($query) => $query->where('brc_id', $registration->brc_id))
                ->update(['status' => 'passive', 'updated_at' => now()]);
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function feeRowsForView(?StudentRegistration $registration): array
    {
        if (! $registration || $registration->fees->isEmpty()) {
            return [[
                'feetype_id' => null,
                'frequency' => '',
                'amount' => '',
                'note' => '',
            ]];
        }

        return $registration->fees->map(function (StudentRegistrationFee $fee): array {
            return [
                'feetype_id' => $fee->feetype_id,
                'frequency' => $fee->frequency,
                'amount' => $fee->amount,
                'note' => $fee->note,
            ];
        })->all();
    }

    private function nextRegistrationNumber(int $branchId): string
    {
        $settings = null;

        if (Schema::hasTable((new Setting)->getTable())) {
            $settings = Setting::query()
                ->when($branchId, fn ($query) => $query->where('brc_id', $branchId))
                ->latest('created_at')
                ->first() ?? Setting::query()->latest('created_at')->first();
        }

        $prefix = (string) ($settings?->regd_prefix ?: 'REG');
        $digits = max(1, (int) ($settings?->regd_no_digit ?: 4));
        $startFrom = (int) ($settings?->regd_start_from ?: 1);
        $updateStatus = (int) ($settings?->regd_update_status ?? 1);
        $autoInsert = (int) ($settings?->regd_auto_insert ?? 1);

        $lastRegistration = null;

        if (Schema::hasTable((new StudentRegistration)->getTable())) {
            $lastRegistration = StudentRegistration::query()
                ->when($branchId, fn ($query) => $query->where('brc_id', $branchId))
                ->latest('id')
                ->first();
        }

        if ($autoInsert === 1 && $lastRegistration?->regd_no) {
            $numeric = (int) preg_replace('/\D+/', '', (string) $lastRegistration->regd_no);
            $base = $updateStatus === 1 ? max($numeric + 1, $startFrom) : $startFrom;

            return $prefix.str_pad((string) $base, $digits, '0', STR_PAD_LEFT);
        }

        $numeric = $lastRegistration?->regd_no ? (int) preg_replace('/\D+/', '', (string) $lastRegistration->regd_no) : 0;
        $base = $numeric > 0 ? $numeric + 1 : $startFrom;

        return $prefix.str_pad((string) $base, $digits, '0', STR_PAD_LEFT);
    }

    private function normalizeCountryCode(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : preg_replace('/\D+/', '', $value);
    }

    private function normalizeGuardianRelation(string $value): ?string
    {
        $normalized = strtolower(trim($value));

        return match ($normalized) {
            'father' => 'Father',
            'mother' => 'Mother',
            'other' => 'Other',
            default => $value === '' ? null : $value,
        };
    }

    private function generatedPassword(): string
    {
        return (string) random_int(100000, 999999);
    }

    /**
     * @param  callable(): Collection<int, mixed>  $callback
     */
    private function collectionIfTableExists(string $table, callable $callback): Collection
    {
        if (! Schema::hasTable($table)) {
            return collect();
        }

        return $callback();
    }

    private function registrationPaginator(int $branchId, string $search, Request $request, string $registrationTable): LengthAwarePaginator
    {
        if (! Schema::hasTable($registrationTable)) {
            return new LengthAwarePaginator([], 0, 15, 1, [
                'path' => $request->url(),
                'query' => $request->query(),
            ]);
        }

        return StudentRegistration::query()
            ->with(['fees'])
            ->when($branchId, fn ($query) => $query->where('brc_id', $branchId))
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('regd_no', 'like', "%{$search}%")
                        ->orWhere('firstname', 'like', "%{$search}%")
                        ->orWhere('lastname', 'like', "%{$search}%")
                        ->orWhere('father_name', 'like', "%{$search}%")
                        ->orWhere('mobileno', 'like', "%{$search}%");
                });
            })
            ->latest('id')
            ->paginate(15)
            ->withQueryString();
    }
}
