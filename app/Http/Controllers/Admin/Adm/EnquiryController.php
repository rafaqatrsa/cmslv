<?php

namespace App\Http\Controllers\Admin\Adm;

use App\Http\Requests\Admin\Adm\EnquiryUpdateRequest;
use App\Http\Requests\Admin\Adm\FollowUpStoreRequest;
use App\Models\Adm\Enquiry;
use App\Models\Branch;
use App\Models\Staff;
use App\Services\BranchContext;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class EnquiryController extends BaseAdmController
{
    public function index(Request $request): View
    {
        return view('admin.adm.enquiries.index', array_merge(
            $this->viewData($request),
            $this->formData($request),
        ));
    }

    public function data(Request $request): JsonResponse
    {
        $records = $this->viewData($request)['records'];

        return response()->json([
            'data' => collect($records->items())->map(fn (Enquiry $record): array => [
                'id' => $record->id,
                'date' => $record->formatted_date,
                'name' => $record->name ?: 'N/A',
                'contact' => $record->contact ?: $record->phone ?: 'N/A',
                'relation' => $record->visitor_relation_label,
                'source' => $record->source_label,
                'reference' => $this->referenceLabel($record),
                'assigned_to' => $record->assigned_to_label,
                'follow_up' => $this->followUpCount($record->id).' times',
                'status' => $record->status_label,
            ])->values(),
            'current_page' => $records->currentPage(),
            'last_page' => $records->lastPage(),
            'total' => $records->total(),
        ]);
    }

    public function details(int $id): JsonResponse
    {
        $enquiry = $this->findEnquiry($id);

        return response()->json([
            'record' => $this->recordPayload($enquiry),
            'kids' => $this->kids($id),
            'options' => $this->editOptions(),
        ]);
    }

    public function update(EnquiryUpdateRequest $request, int $id): JsonResponse
    {
        $enquiry = $this->findEnquiry($id);
        $validated = $request->validated();
        $table = $enquiry->getTable();
        $columns = Schema::getColumnListing($table);
        $payload = collect($request->all())->only([
            'brc_id', 'enquiry_no', 'name', 'country_code', 'contact', 'visitor_relation', 'idcard', 'id_card',
            'email', 'father_name', 'occupation_id', 'address', 'landline_no', 'phone', 'whatsapp_country_code',
            'whatsapp', 'reference', 'date', 'description', 'follow_up_date', 'note', 'fee_package_policy_class_id',
            'source', 'assigned',
        ])->all();
        $payload['id_card'] = $payload['id_card'] ?? ($payload['idcard'] ?? null);
        unset($payload['idcard']);
        $payload['date'] = Carbon::parse($validated['date'])->toDateString();
        $payload['follow_up_date'] = Carbon::parse($validated['follow_up_date'])->toDateString();
        $payload['fee_package_policy_json'] = ! empty($validated['fee_policy']) ? json_encode($validated['fee_policy']) : null;
        $payload['updated_by'] = $this->staffId();
        $payload['updated_at'] = now();

        $enquiry->fill(array_intersect_key($payload, array_flip($columns)))->save();
        $this->syncKids($id, $request);

        return response()->json([
            'message' => 'Admission enquiry updated successfully.',
            'record' => $this->recordPayload($this->findEnquiry($id)),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $enquiry = $this->findEnquiry($id);

        DB::transaction(function () use ($id, $enquiry): void {
            if (Schema::hasTable('enquiry_kid')) {
                DB::table('enquiry_kid')->where('enquiry_id', $id)->delete();
            }
            if (Schema::hasTable('follow_up')) {
                DB::table('follow_up')->where('enquiry_id', $id)->delete();
            }
            $enquiry->delete();
        });

        return response()->json(['message' => 'Admission enquiry deleted successfully.']);
    }

    public function followUps(int $id): JsonResponse
    {
        $enquiry = $this->findEnquiry($id);

        return response()->json([
            'enquiry' => ['id' => $enquiry->id, 'name' => $enquiry->name, 'status' => $enquiry->status],
            'follow_ups' => $this->followUpRows($id),
            'count' => $this->followUpCount($id),
        ]);
    }

    public function storeFollowUp(FollowUpStoreRequest $request, int $id): JsonResponse
    {
        $this->findEnquiry($id);
        abort_unless(Schema::hasTable('follow_up'), 500, 'Follow-up table is not available.');
        $columns = Schema::getColumnListing('follow_up');
        $payload = [
            'enquiry_id' => $id,
            'date' => Carbon::parse($request->validated('date'))->toDateString(),
            'next_date' => Carbon::parse($request->validated('follow_up_date'))->toDateString(),
            'response' => $request->validated('response'),
            'note' => $request->validated('note'),
            'followup_by' => $this->staffId(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('follow_up')->insert(array_intersect_key($payload, array_flip($columns)));

        return response()->json([
            'message' => 'Follow-up added successfully.',
            'follow_ups' => $this->followUpRows($id),
            'count' => $this->followUpCount($id),
        ]);
    }

    public function destroyFollowUp(int $id, int $followUpId): JsonResponse
    {
        $this->findEnquiry($id);
        abort_unless(Schema::hasTable('follow_up'), 404);
        DB::table('follow_up')->where('id', $followUpId)->where('enquiry_id', $id)->delete();

        return response()->json([
            'message' => 'Follow-up deleted successfully.',
            'follow_ups' => $this->followUpRows($id),
            'count' => $this->followUpCount($id),
        ]);
    }

    public function changeStatus(Request $request, int $id): JsonResponse
    {
        $enquiry = $this->findEnquiry($id);
        $status = $request->validate(['status' => ['required', 'string', 'max:50']])['status'];
        $allowedStatus = $this->statuses()->first(fn (string $allowed): bool => strcasecmp($allowed, $status) === 0);
        abort_unless($allowedStatus !== null, 422, 'Invalid status.');
        $status = $allowedStatus;
        $enquiry->forceFill(['status' => $status, 'updated_by' => $this->staffId()])->save();

        return response()->json(['message' => 'Status updated successfully.', 'status' => $status]);
    }

    public function create(Request $request): View
    {
        return view('admin.adm.enquiries.create', $this->formData($request));
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'brc_id' => ['nullable', 'integer'],
            'name' => ['required', 'string', 'max:150'],
            'contact' => ['required', 'string', 'max:30'],
            'source' => ['required', 'string', 'max:100'],
            'date' => ['required', 'date'],
            'follow_up_date' => ['required', 'date'],
            'email' => ['nullable', 'email', 'max:150'],
            'class_id' => ['nullable', 'array'],
            'class_id.*' => ['nullable', 'integer'],
            'kid_name' => ['nullable', 'array'],
            'number_of_kids' => ['nullable', 'array'],
            'fee_policy' => ['nullable', 'array'],
        ]);

        $branchId = (int) ($validated['brc_id'] ?? app(BranchContext::class)->id() ?? 0);
        $payload = collect($request->all())->only([
            'brc_id', 'enquiry_no', 'name', 'country_code', 'contact', 'visitor_relation', 'idcard', 'id_card',
            'email', 'father_name', 'occupation_id', 'address', 'landline_no', 'phone', 'whatsapp_country_code',
            'whatsapp', 'reference', 'date', 'description', 'follow_up_date', 'note', 'fee_package_policy_class_id',
            'source', 'assigned',
        ])->all();

        $payload['brc_id'] = $branchId ?: null;
        $payload['enquiry_no'] = $payload['enquiry_no'] ?? 'ENQ-'.now()->format('YmdHis');
        $payload['id_card'] = $payload['id_card'] ?? ($payload['idcard'] ?? null);
        unset($payload['idcard']);
        $payload['date'] = Carbon::parse($payload['date'])->toDateString();
        $payload['follow_up_date'] = Carbon::parse($payload['follow_up_date'])->toDateString();
        $payload['fee_package_policy_json'] = ! empty($validated['fee_policy']) ? json_encode($validated['fee_policy']) : null;
        $payload['status'] = 'active';
        $payload['created_by'] = $this->staffId();
        $payload['created_at'] = now();
        $payload['updated_at'] = now();

        $table = (new Enquiry)->getTable();
        $columns = Schema::hasTable($table) ? Schema::getColumnListing($table) : [];
        $payload = array_intersect_key($payload, array_flip($columns));

        abort_unless($columns !== [], 500, 'Admission enquiry table is not available.');

        DB::transaction(function () use ($payload, $request): void {
            $enquiryId = DB::table('enquiry')->insertGetId($payload);

            if (! Schema::hasTable('enquiry_kid')) {
                return;
            }

            $classIds = $request->input('class_id', []);
            $kidNames = $request->input('kid_name', []);
            $kidCounts = $request->input('number_of_kids', []);

            foreach ($classIds as $index => $classId) {
                if (! $classId) {
                    continue;
                }

                $kidPayload = [
                    'enquiry_id' => $enquiryId,
                    'class_id' => $classId,
                    'kid_name' => $kidNames[$index] ?? null,
                    'number_of_kids' => $kidCounts[$index] ?? 1,
                ];
                $kidColumns = Schema::getColumnListing('enquiry_kid');
                DB::table('enquiry_kid')->insert(array_intersect_key($kidPayload, array_flip($kidColumns)));
            }
        });

        $message = 'Admission enquiry added successfully.';

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'redirect_url' => route('admin.adm.enquiries.index', absolute: false),
            ]);
        }

        return to_route('admin.adm.enquiries.index')->with('success', $message);
    }

    public function feeStructure(Request $request): JsonResponse
    {
        if (! Schema::hasTable('fee_groups_feetype')) {
            return response()->json([]);
        }

        $columns = Schema::getColumnListing('fee_groups_feetype');
        $query = DB::table('fee_groups_feetype')->select('fee_groups_feetype.*');

        if (Schema::hasTable('accountshead') && in_array('feetype_id', $columns, true)) {
            $query->leftJoin('accountshead', 'accountshead.id', '=', 'fee_groups_feetype.feetype_id')
                ->addSelect('accountshead.name as fee_type_name');
        }

        $query->when($request->filled('class_id') && in_array('fee_class_id', $columns, true), fn ($query) => $query->where('fee_class_id', $request->integer('class_id')))
            ->when($request->filled('brc_id') && in_array('brc_id', $columns, true), fn ($query) => $query->where('brc_id', $request->integer('brc_id')));

        return response()->json($query->get());
    }

    public function checkNumber(Request $request): JsonResponse
    {
        $phoneNumber = preg_replace('/\D+/', '', (string) $request->input('phone_number', ''));

        if ($phoneNumber === '' || ! Schema::hasTable('enquiry')) {
            return response()->json(['status' => 'fail', 'message' => '']);
        }

        $columns = Schema::getColumnListing('enquiry');
        $record = Enquiry::query()
            ->where(function (Builder $query) use ($columns, $phoneNumber): void {
                foreach (array_intersect(['contact', 'phone'], $columns) as $column) {
                    $query->orWhereRaw("REPLACE(REPLACE(REPLACE(REPLACE({$column}, ' ', ''), '-', ''), '+', ''), '(', '') LIKE ?", ["%{$phoneNumber}%"]);
                }
            })
            ->latest('id')
            ->first(['id', 'name']);

        return response()->json($record
            ? ['status' => 'success', 'message' => "Number already exists and name is {$record->name}", 'name' => $record->name]
            : ['status' => 'fail', 'message' => '']);
    }

    public function addOccupation(Request $request): JsonResponse
    {
        return $this->addMasterOption($request, 'occupation', 'name', 'Occupation');
    }

    public function addReference(Request $request): JsonResponse
    {
        return $this->addMasterOption($request, 'reference', 'reference', 'Reference');
    }

    public function addSource(Request $request): JsonResponse
    {
        return $this->addMasterOption($request, 'source', 'source', 'Source');
    }

    /**
     * @return array<string, mixed>
     */
    private function viewData(Request $request): array
    {
        $records = $this->records($request);
        $staffNames = $this->assignedStaffNames($records);
        $selectedBranchId = $this->selectedBranchId($request);

        $records->getCollection()->transform(function (Enquiry $enquiry) use ($staffNames): Enquiry {
            $enquiry->setAttribute('formatted_date', $this->formatDate($enquiry->date ?? $enquiry->created_at));
            $enquiry->setAttribute('formatted_follow_up_date', $this->formatDate($enquiry->follow_up_date));
            $enquiry->setAttribute('visitor_relation_label', $this->visitorRelation($enquiry));
            $enquiry->setAttribute('source_label', $this->sourceLabel($enquiry));
            $enquiry->setAttribute('assigned_to_label', $this->assignedToLabel($enquiry, $staffNames));
            $enquiry->setAttribute('status_label', $this->statusLabel($enquiry));
            $enquiry->setAttribute('follow_up_count', $this->followUpCount($enquiry->id));

            return $enquiry;
        });

        return [
            'branches' => $this->collectionIfTableExists((new Branch)->getTable(), fn () => Branch::query()->active()->orderBy('name')->get(['id', 'name'])),
            'classes' => $this->collectionIfTableExists('classes', fn () => DB::table('classes')->orderBy('class')->get(['id', 'class'])),
            'sources' => $this->sources(),
            'statuses' => $this->statuses(),
            'records' => $records,
            'selectedBranch' => $selectedBranchId ? (string) $selectedBranchId : '',
            'selectedClass' => $request->string('class_id')->toString(),
            'selectedSource' => $request->string('source')->toString(),
            'selectedStatus' => $this->selectedStatus($request),
            'selectedSearch' => $request->string('search')->toString(),
            'selectedDateFrom' => $request->string('date_from')->toString(),
            'selectedDateTo' => $request->string('date_to')->toString(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function formData(Request $request): array
    {
        $branchId = $this->selectedBranchId($request);

        return [
            'branches' => $this->collectionIfTableExists('branch', fn () => Branch::query()->active()->orderBy('name')->get(['id', 'name'])),
            'classes' => $this->collectionIfTableExists('classes', fn () => DB::table('classes')->orderBy('class')->get(['id', 'class'])),
            'sources' => $this->sources(),
            'occupations' => $this->simpleOptions('occupation', ['id', 'name'], 'name'),
            'references' => $this->simpleOptions('reference', ['id', 'reference'], 'reference'),
            'staff' => $this->collectionIfTableExists('staff', fn () => Staff::query()->orderBy('name')->get(['id', 'name'])),
            'branchId' => $branchId,
            'enquiryNo' => 'ENQ-'.now()->format('YmdHis'),
        ];
    }

    /**
     * @param  array<int, string>  $columns
     * @return Collection<int, object>
     */
    private function simpleOptions(string $table, array $columns, string $orderBy): Collection
    {
        if (! Schema::hasTable($table) || ! in_array($orderBy, Schema::getColumnListing($table), true)) {
            return collect();
        }

        return DB::table($table)->orderBy($orderBy)->get($columns);
    }

    /**
     * @return array<string, array<int, array<string, mixed>>>
     */
    private function editOptions(): array
    {
        return [
            'classes' => $this->simpleOptions('classes', ['id', 'class'], 'class')->map(fn (object $item): array => ['id' => $item->id, 'label' => $item->class])->values()->all(),
            'occupations' => $this->simpleOptions('occupation', ['id', 'name'], 'name')->map(fn (object $item): array => ['id' => $item->id, 'label' => $item->name])->values()->all(),
            'references' => $this->simpleOptions('reference', ['id', 'reference'], 'reference')->map(fn (object $item): array => ['id' => $item->id, 'label' => $item->reference])->values()->all(),
            'sources' => $this->simpleOptions('source', ['id', 'source'], 'source')->map(fn (object $item): array => ['id' => $item->id, 'label' => $item->source])->values()->all(),
            'staff' => $this->collectionIfTableExists('staff', fn () => Staff::query()->orderBy('name')->get(['id', 'name']))->map(fn (object $item): array => ['id' => $item->id, 'label' => trim($item->name.' '.($item->surname ?? ''))])->values()->all(),
        ];
    }

    private function records(Request $request): LengthAwarePaginator
    {
        $table = (new Enquiry)->getTable();
        $branchId = $this->selectedBranchId($request);
        $status = $this->selectedStatus($request);

        if (! Schema::hasTable($table)) {
            return new Paginator([], 0, 10, 1, [
                'path' => $request->url(),
                'query' => $request->query(),
            ]);
        }

        $columns = Schema::getColumnListing($table);

        $query = Enquiry::query()
            ->when($branchId && in_array('brc_id', $columns, true), fn (Builder $query) => $query->where('brc_id', $branchId))
            ->when($request->filled('class_id') && in_array('class_id', $columns, true), fn (Builder $query) => $query->where('class_id', $request->integer('class_id')))
            ->when($request->filled('source') && in_array('source', $columns, true), fn (Builder $query) => $query->where('source', $request->string('source')->toString()))
            ->when($status !== '' && strtolower($status) !== 'all' && in_array('status', $columns, true), fn (Builder $query) => $query->whereRaw('LOWER(status) = ?', [strtolower($status)]))
            ->when($request->filled('date_from') && in_array('date', $columns, true), fn (Builder $query) => $query->whereDate('date', '>=', $request->date('date_from')))
            ->when($request->filled('date_to') && in_array('date', $columns, true), fn (Builder $query) => $query->whereDate('date', '<=', $request->date('date_to')))
            ->when($request->filled('search'), function (Builder $query) use ($columns, $request): void {
                $searchable = array_values(array_intersect([
                    'enquiry_no',
                    'name',
                    'contact',
                    'phone',
                    'father_name',
                    'reference',
                    'source',
                    'guardian_relation',
                    'visitor_relation',
                    'status',
                    'description',
                    'note',
                ], $columns));
                $search = $request->string('search')->toString();

                if ($searchable === []) {
                    return;
                }

                $query->where(function (Builder $query) use ($columns, $searchable, $search): void {
                    foreach ($searchable as $column) {
                        $query->orWhere($column, 'like', "%{$search}%");
                    }

                    if (Schema::hasTable('staff')) {
                        $staffIds = Staff::query()
                            ->where('name', 'like', "%{$search}%")
                            ->pluck('id');

                        if ($staffIds->isNotEmpty()) {
                            if (in_array('assigned', $columns, true)) {
                                $query->orWhereIn('assigned', $staffIds);
                            }
                            if (in_array('created_by', $columns, true)) {
                                $query->orWhereIn('created_by', $staffIds);
                            }
                        }
                    }

                    if (Schema::hasTable('source')) {
                        $sourceIds = DB::table('source')
                            ->where('source', 'like', "%{$search}%")
                            ->pluck('id');

                        if ($sourceIds->isNotEmpty() && in_array('source', $columns, true)) {
                            $query->orWhereIn('source', $sourceIds);
                        }
                    }

                    if (Schema::hasTable('reference')) {
                        $referenceIds = DB::table('reference')
                            ->where('reference', 'like', "%{$search}%")
                            ->pluck('id');

                        if ($referenceIds->isNotEmpty() && in_array('reference', $columns, true)) {
                            $query->orWhereIn('reference', $referenceIds);
                        }
                    }
                });
            });

        $sortMap = [
            'date' => 'date',
            'name' => 'name',
            'source' => 'source',
            'reference' => 'reference',
            'status' => 'status',
        ];
        $requestedSort = $request->string('sort')->toString();
        $sortColumn = isset($sortMap[$requestedSort]) && in_array($sortMap[$requestedSort], $columns, true)
            ? $sortMap[$requestedSort]
            : 'id';
        $direction = $request->string('direction')->toString() === 'asc' ? 'asc' : 'desc';

        return $query
            ->orderBy($sortColumn, $direction)
            ->when($sortColumn !== 'id' && in_array('id', $columns, true), fn (Builder $query): Builder => $query->orderByDesc('id'))
            ->paginate(10)
            ->withQueryString();
    }

    private function selectedBranchId(Request $request): ?int
    {
        if ($request->filled('brc_id')) {
            return $request->integer('brc_id');
        }

        $branchId = app(BranchContext::class)->id();

        if ($branchId) {
            return $branchId;
        }

        $table = (new Branch)->getTable();

        if (! Schema::hasTable($table)) {
            return null;
        }

        return Branch::query()->active()->orderBy('id')->value('id');
    }

    private function findEnquiry(int $id): Enquiry
    {
        $enquiry = Enquiry::query()->findOrFail($id);
        $branchId = app(BranchContext::class)->id();

        abort_if($branchId && (int) ($enquiry->brc_id ?? 0) !== (int) $branchId, 403);

        return $enquiry;
    }

    /**
     * @return array<string, mixed>
     */
    private function recordPayload(Enquiry $enquiry): array
    {
        $attributes = $enquiry->getAttributes();
        $attributes['formatted_date'] = $this->formatDate($enquiry->date ?? $enquiry->created_at);
        $attributes['visitor_relation_label'] = $this->visitorRelation($enquiry);
        $attributes['source_label'] = $this->sourceLabel($enquiry);
        $attributes['reference_label'] = $this->referenceLabel($enquiry);
        $attributes['assigned_to_label'] = $this->assignedToLabel($enquiry, $this->assignedStaffNamesForEnquiry($enquiry));
        $attributes['status_label'] = $this->statusLabel($enquiry);

        return $attributes;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function kids(int $enquiryId): array
    {
        if (! Schema::hasTable('enquiry_kid')) {
            return [];
        }

        $columns = Schema::getColumnListing('enquiry_kid');
        $query = DB::table('enquiry_kid')->where('enquiry_id', $enquiryId)->orderBy('id');

        if (Schema::hasTable('classes') && in_array('class_id', $columns, true)) {
            $query->leftJoin('classes', 'classes.id', '=', 'enquiry_kid.class_id')
                ->addSelect('enquiry_kid.*', 'classes.class as class_name');
        }

        return $query->get()->map(fn (object $kid): array => (array) $kid)->all();
    }

    private function syncKids(int $enquiryId, Request $request): void
    {
        if (! Schema::hasTable('enquiry_kid')) {
            return;
        }

        $allIds = array_filter((array) $request->input('enkidallid', []));
        $postedIds = array_filter((array) $request->input('enkidid', []));
        DB::table('enquiry_kid')->where('enquiry_id', $enquiryId)->whereIn('id', array_diff($allIds, $postedIds))->delete();
        $columns = Schema::getColumnListing('enquiry_kid');

        foreach ((array) $request->input('class_id', []) as $index => $classId) {
            if (! $classId) {
                continue;
            }

            $payload = [
                'id' => $postedIds[$index] ?? null,
                'enquiry_id' => $enquiryId,
                'class_id' => $classId,
                'kid_name' => data_get($request->input('kid_name', []), $index),
                'number_of_kids' => data_get($request->input('number_of_kids', []), $index, 0),
            ];
            $payload = array_intersect_key($payload, array_flip($columns));
            $kidId = $postedIds[$index] ?? null;

            if ($kidId) {
                DB::table('enquiry_kid')->where('id', $kidId)->where('enquiry_id', $enquiryId)->update($payload);
            } else {
                DB::table('enquiry_kid')->insert($payload);
            }
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function followUpRows(int $enquiryId): array
    {
        if (! Schema::hasTable('follow_up')) {
            return [];
        }

        $query = DB::table('follow_up')->where('enquiry_id', $enquiryId)->orderByDesc('id');
        $rows = $query->get();

        return $rows->map(function (object $row): array {
            $data = (array) $row;
            $staff = ! empty($data['followup_by']) && Schema::hasTable('staff')
                ? Staff::query()->find($data['followup_by'])
                : null;
            $data['followup_by_label'] = $staff?->name ?? 'N/A';
            $data['date_label'] = $this->formatDate($data['date'] ?? null);
            $data['next_date_label'] = $this->formatDate($data['next_date'] ?? null);

            return $data;
        })->all();
    }

    private function followUpCount(int $enquiryId): int
    {
        if (! Schema::hasTable('follow_up')) {
            return 0;
        }

        return (int) DB::table('follow_up')
            ->where('enquiry_id', $enquiryId)
            ->whereRaw("TRIM(COALESCE(response, '')) != ''")
            ->count();
    }

    private function assignedStaffNamesForEnquiry(Enquiry $enquiry): Collection
    {
        $ids = collect([$enquiry->assigned, $enquiry->created_by, $enquiry->updated_by])
            ->filter(fn (mixed $id): bool => is_numeric($id) && (int) $id > 0)
            ->map(fn (mixed $id): int => (int) $id)
            ->unique();

        return Schema::hasTable('staff') && $ids->isNotEmpty()
            ? Staff::query()->whereIn('id', $ids)->pluck('name', 'id')
            : collect();
    }

    private function selectedStatus(Request $request): string
    {
        return $request->filled('status') ? $request->string('status')->toString() : 'Active';
    }

    /**
     * @return Collection<int, mixed>
     */
    private function sources(): Collection
    {
        if (Schema::hasTable('source')) {
            return DB::table('source')->orderBy('source')->get(['id', 'source']);
        }

        if (! Schema::hasTable((new Enquiry)->getTable())) {
            return collect();
        }

        return Enquiry::query()
            ->whereNotNull('source')
            ->distinct()
            ->orderBy('source')
            ->get(['source'])
            ->map(fn (Enquiry $enquiry): object => (object) [
                'id' => $enquiry->source,
                'source' => $enquiry->source,
            ]);
    }

    /**
     * @return Collection<int, string>
     */
    private function statuses(): Collection
    {
        $table = (new Enquiry)->getTable();

        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'status')) {
            return collect(['Active']);
        }

        $statuses = Enquiry::query()
            ->whereNotNull('status')
            ->distinct()
            ->pluck('status')
            ->filter()
            ->map(fn (mixed $status): string => (string) $status)
            ->values();

        return $statuses->isNotEmpty() ? $statuses : collect(['Active']);
    }

    /**
     * @return Collection<int, string>
     */
    private function assignedStaffNames(LengthAwarePaginator $records): Collection
    {
        if (! Schema::hasTable((new Staff)->getTable())) {
            return collect();
        }

        $staffIds = collect($records->items())
            ->pluck('assigned')
            ->merge(collect($records->items())->pluck('created_by'))
            ->merge(collect($records->items())->pluck('updated_by'))
            ->filter(fn (mixed $value): bool => is_numeric($value) && (int) $value > 0)
            ->map(fn (mixed $value): int => (int) $value)
            ->unique()
            ->values();

        if ($staffIds->isEmpty()) {
            return collect();
        }

        return Staff::query()
            ->whereIn('id', $staffIds)
            ->pluck('name', 'id');
    }

    private function visitorRelation(Enquiry $enquiry): string
    {
        $relation = $enquiry->guardian_relation ?? data_get($enquiry, 'relation') ?? data_get($enquiry, 'visitor_relation');

        return match ((string) $relation) {
            '1' => 'Father',
            '2' => 'Mother',
            '3' => 'Other',
            default => (string) ($relation ?: 'N/A'),
        };
    }

    private function sourceLabel(Enquiry $enquiry): string
    {
        $source = trim((string) ($enquiry->source ?? ''));

        if ($source === '') {
            return 'N/A';
        }

        if (Schema::hasTable('source') && is_numeric($source)) {
            $sourceName = DB::table('source')->where('id', $source)->value('source');
            if ($sourceName) {
                return (string) $sourceName;
            }
        }

        return str($source)->replace(['_', '-'], ' ')->title()->toString();
    }

    private function referenceLabel(Enquiry $enquiry): string
    {
        $reference = trim((string) ($enquiry->reference ?? ''));

        if ($reference === '') {
            return 'N/A';
        }

        if (Schema::hasTable('reference') && is_numeric($reference)) {
            return (string) (DB::table('reference')->where('id', $reference)->value('reference') ?: $reference);
        }

        return $reference;
    }

    private function assignedToLabel(Enquiry $enquiry, Collection $staffNames): string
    {
        $assignedBy = $enquiry->assigned ?? $enquiry->created_by ?? $enquiry->updated_by;

        if (is_numeric($assignedBy) && (int) $assignedBy > 0 && $staffNames->has((int) $assignedBy)) {
            return (string) $staffNames->get((int) $assignedBy);
        }

        if ((int) $assignedBy === 0) {
            return 'System';
        }

        return 'N/A';
    }

    private function statusLabel(Enquiry $enquiry): string
    {
        $status = trim((string) ($enquiry->status ?? ''));

        if ($status === '') {
            return 'Active';
        }

        return str($status)->replace(['_', '-'], ' ')->title()->toString();
    }

    private function formatDate(mixed $value): string
    {
        if (! $value) {
            return '--';
        }

        try {
            return Carbon::parse($value)->format('d/m/Y');
        } catch (\Throwable $throwable) {
            return (string) $value;
        }
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

    private function addMasterOption(Request $request, string $table, string $nameColumn, string $label): JsonResponse
    {
        $name = trim((string) $request->input('name', ''));
        $validator = validator(['name' => $name], ['name' => ['required', 'string', 'max:150']]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail',
                'error' => ['name' => $validator->errors()->first('name')],
                'message' => '',
            ], 422);
        }

        abort_unless(Schema::hasTable($table), 500, "{$label} table is not available.");
        $columns = Schema::getColumnListing($table);
        abort_unless(in_array($nameColumn, $columns, true), 500, "{$label} column is not available.");

        $existing = DB::table($table)->whereRaw("LOWER({$nameColumn}) = ?", [strtolower($name)])->first();
        if ($existing) {
            return response()->json([
                'status' => 'fail',
                'error' => ['name' => 'Record already exists'],
                'message' => 'Record already exists',
            ], 422);
        }

        $payload = [$nameColumn => $name];
        if (in_array('is_active', $columns, true)) {
            $payload['is_active'] = 1;
        }
        if (in_array('created_at', $columns, true)) {
            $payload['created_at'] = now();
        }
        if (in_array('updated_at', $columns, true)) {
            $payload['updated_at'] = now();
        }

        $id = DB::table($table)->insertGetId($payload);

        return response()->json([
            'status' => 'success',
            'message' => "{$label} added successfully",
            'data' => ['id' => $id, 'name' => $name],
        ]);
    }

    private function staffId(): ?int
    {
        $userId = auth()->id();

        if (! $userId) {
            return null;
        }

        if (Schema::hasTable('staff') && Schema::hasColumn('staff', 'user_id')) {
            return (int) (Staff::query()->where('user_id', $userId)->value('id') ?? $userId);
        }

        return (int) $userId;
    }
}
