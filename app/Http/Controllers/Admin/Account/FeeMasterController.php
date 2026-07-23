<?php

namespace App\Http\Controllers\Admin\Account;

use App\Services\AcademicSessionContext;
use App\Services\BranchContext;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FeeMasterController extends BaseAccountController
{
    public function index(Request $request, ?int $branch = null): View
    {
        return $this->feeStructureView($request, $branch);
    }

    public function store(Request $request, ?int $branch = null): RedirectResponse
    {
        return $this->saveFeeStructure($request, $branch);
    }

    public function edit(Request $request, int $feeMaster, ?int $branch = null): View
    {
        return $this->feeStructureView($request, $branch, $feeMaster);
    }

    public function update(Request $request, int $feeMaster, ?int $branch = null): RedirectResponse
    {
        return $this->saveFeeStructure($request, $branch, $feeMaster);
    }

    public function destroy(int $feeMaster, ?int $branch = null): RedirectResponse
    {
        $branchId = $this->selectedBranchId($branch);

        if (Schema::hasTable('fee_groups_feetype')) {
            $feeRecord = DB::table('fee_groups_feetype')
                ->where('id', $feeMaster)
                ->when($branchId, fn (Builder $query) => $query->where('brc_id', $branchId))
                ->first();

            if ($feeRecord) {
                DB::table('fee_groups_feetype')
                    ->where('id', $feeMaster)
                    ->when($branchId, fn (Builder $query) => $query->where('brc_id', $branchId))
                    ->delete();

                $this->removeEmptyFeeGroup($feeRecord, $branchId);
            }
        }

        return redirect()
            ->route('admin.account.fee-master.index.legacy', ['branch' => $branchId], false)
            ->with('success', 'Fee Structure deleted successfully');
    }

    private function feeStructureView(Request $request, ?int $branch = null, ?int $feeMaster = null): View
    {
        $branchId = $this->selectedBranchId($branch);
        $editingFee = $this->feeStructureRecord($feeMaster, $branchId);

        return view('admin.account.feemaster.index', [
            'title' => $editingFee ? 'Edit Fee Structure' : 'Add Fee Structure',
            'branchId' => $branchId,
            'branches' => $this->branches(),
            'classes' => $this->classes(),
            'feeTypes' => $this->feeTypes($branchId),
            'feeGroups' => $this->feeGroups($branchId),
            'editingFee' => $editingFee,
            'sessionLabel' => $this->currentSessionLabel($branchId),
            'showMonthCount' => $this->showMonthCount($branchId),
            'currencySymbol' => 'Rs.',
            'pdfUrl' => url('/admin/report/pdffeestructurereport?brc_id='.$branchId),
        ]);
    }

    private function saveFeeStructure(Request $request, ?int $branch = null, ?int $feeMaster = null): RedirectResponse
    {
        $branchId = $this->selectedBranchId($branch);

        $validated = $request->validate([
            'brc_id' => ['nullable', 'integer'],
            'class_id' => ['required', 'integer'],
            'frequency' => ['required', Rule::in(['One Time', 'Monthly', 'Yearly'])],
            'month_count' => ['nullable', 'integer', 'min:0'],
            'feetype_id' => ['required', 'integer'],
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        $branchId = (int) ($validated['brc_id'] ?? $branchId);
        $classId = (int) $validated['class_id'];
        $feeTypeId = (int) $validated['feetype_id'];

        if ($this->feeCombinationExists($classId, $feeTypeId, $branchId, $feeMaster)) {
            return back()
                ->withInput()
                ->withErrors(['class_id' => 'Fee combination already exists']);
        }

        if (! Schema::hasTable('fee_groups_feetype')) {
            return back()
                ->withInput()
                ->withErrors(['class_id' => 'Fee structure table is not available in this environment.']);
        }

        $feeGroupId = $this->feeGroupId($classId, $branchId);
        $columns = Schema::getColumnListing('fee_groups_feetype');
        $payload = $this->existingColumnsOnly($columns, [
            'brc_id' => $branchId,
            'fee_class_id' => $classId,
            'fee_class_group_id' => $feeGroupId,
            'feetype_id' => $feeTypeId,
            'amount' => $validated['amount'],
            'frequency' => $validated['frequency'],
            'month_count' => (int) ($validated['month_count'] ?? 0),
            'note' => $validated['description'] ?? null,
            'session_id' => app(AcademicSessionContext::class)->id(),
        ]);

        if ($feeMaster) {
            DB::table('fee_groups_feetype')
                ->where('id', $feeMaster)
                ->when($branchId, fn (Builder $query) => $query->where('brc_id', $branchId))
                ->update($payload);

            $message = 'Fees Master updated successfully';
        } else {
            DB::table('fee_groups_feetype')->insert($payload);
            $message = 'Fee Structure added successfully';
        }

        return redirect()
            ->route('admin.account.fee-master.index.legacy', ['branch' => $branchId], false)
            ->with('success', $message);
    }

    private function selectedBranchId(?int $branch = null): int
    {
        return $branch ?: app(BranchContext::class)->id() ?: 1;
    }

    /**
     * @return Collection<int, object>
     */
    private function branches(): Collection
    {
        if (! Schema::hasTable('branch')) {
            return collect();
        }

        return DB::table('branch')
            ->select($this->selectExistingColumns('branch', ['id', 'name']))
            ->orderBy('id')
            ->get();
    }

    /**
     * @return Collection<int, object>
     */
    private function classes(): Collection
    {
        if (! Schema::hasTable('classes')) {
            return collect();
        }

        $columns = $this->selectExistingColumns('classes', ['id', 'class']);

        return DB::table('classes')
            ->select($columns)
            ->orderBy('id')
            ->get();
    }

    /**
     * @return Collection<int, object>
     */
    private function feeTypes(int $branchId): Collection
    {
        if (! Schema::hasTable('accountshead')) {
            return collect();
        }

        $columns = Schema::getColumnListing('accountshead');

        return DB::table('accountshead')
            ->select($this->selectExistingColumns('accountshead', ['id', 'name']))
            ->when(in_array('new_accounts_id', $columns, true), fn (Builder $query) => $query->where('new_accounts_id', 19))
            ->when(in_array('brc_id', $columns, true), fn (Builder $query) => $query->where(function (Builder $query) use ($branchId): void {
                $query->where('brc_id', $branchId)->orWhereNull('brc_id');
            }))
            ->when(in_array('is_active', $columns, true), fn (Builder $query) => $query->where('is_active', 'yes'))
            ->orderBy('id')
            ->get()
            ->map(function (object $feeType): object {
                $feeType->type = $feeType->name ?? '';

                return $feeType;
            });
    }

    /**
     * @return Collection<int, object>
     */
    private function feeGroups(int $branchId): Collection
    {
        if (! Schema::hasTable('fee_class_groups')) {
            return collect();
        }

        $query = DB::table('fee_class_groups')
            ->select('fee_class_groups.*')
            ->where('fee_class_groups.brc_id', $branchId)
            ->orderBy('fee_class_groups.id');

        if (Schema::hasTable('branch')) {
            $query->leftJoin('branch', 'branch.id', '=', 'fee_class_groups.brc_id')
                ->addSelect('branch.name as branch_name');
        }

        if (Schema::hasTable('classes')) {
            $query->leftJoin('classes', 'classes.id', '=', 'fee_class_groups.fee_class_id')
                ->addSelect('classes.class as class_name');
        }

        return $query->get()->map(function (object $group): object {
            $group->feetypes = $this->feeTypesByClassGroup($group);

            return $group;
        });
    }

    /**
     * @return Collection<int, object>
     */
    private function feeTypesByClassGroup(object $group): Collection
    {
        if (! Schema::hasTable('fee_groups_feetype')) {
            return collect();
        }

        $query = DB::table('fee_groups_feetype')
            ->select('fee_groups_feetype.*')
            ->where('fee_groups_feetype.fee_class_id', $group->fee_class_id)
            ->where('fee_groups_feetype.fee_class_group_id', $group->id)
            ->where('fee_groups_feetype.brc_id', $group->brc_id)
            ->orderBy('fee_groups_feetype.id');

        if (Schema::hasTable('accountshead')) {
            $query->leftJoin('accountshead', 'accountshead.id', '=', 'fee_groups_feetype.feetype_id')
                ->addSelect('accountshead.name as type');
        }

        return $query->get();
    }

    private function feeStructureRecord(?int $feeMaster, int $branchId): ?object
    {
        if (! $feeMaster || ! Schema::hasTable('fee_groups_feetype')) {
            return null;
        }

        $query = DB::table('fee_groups_feetype')
            ->select('fee_groups_feetype.*')
            ->where('fee_groups_feetype.id', $feeMaster)
            ->when($branchId, fn (Builder $query) => $query->where('fee_groups_feetype.brc_id', $branchId));

        if (Schema::hasTable('accountshead')) {
            $query->leftJoin('accountshead', 'accountshead.id', '=', 'fee_groups_feetype.feetype_id')
                ->addSelect('accountshead.name as type');
        }

        return $query->first();
    }

    private function feeCombinationExists(int $classId, int $feeTypeId, int $branchId, ?int $ignoreId = null): bool
    {
        if (! Schema::hasTable('fee_class_groups') || ! Schema::hasTable('fee_groups_feetype')) {
            return false;
        }

        $groupId = DB::table('fee_class_groups')
            ->where('fee_class_id', $classId)
            ->where('brc_id', $branchId)
            ->value('id');

        if (! $groupId) {
            return false;
        }

        return DB::table('fee_groups_feetype')
            ->where('fee_class_group_id', $groupId)
            ->where('fee_class_id', $classId)
            ->where('feetype_id', $feeTypeId)
            ->where('brc_id', $branchId)
            ->when($ignoreId, fn (Builder $query) => $query->where('id', '!=', $ignoreId))
            ->exists();
    }

    private function feeGroupId(int $classId, int $branchId): ?int
    {
        if (! Schema::hasTable('fee_class_groups')) {
            return null;
        }

        $existingId = DB::table('fee_class_groups')
            ->where('fee_class_id', $classId)
            ->where('brc_id', $branchId)
            ->value('id');

        if ($existingId) {
            return (int) $existingId;
        }

        $columns = Schema::getColumnListing('fee_class_groups');
        $payload = $this->existingColumnsOnly($columns, [
            'brc_id' => $branchId,
            'fee_class_id' => $classId,
        ]);

        return DB::table('fee_class_groups')->insertGetId($payload);
    }

    private function removeEmptyFeeGroup(object $feeRecord, int $branchId): void
    {
        if (! Schema::hasTable('fee_class_groups') || ! isset($feeRecord->fee_class_group_id)) {
            return;
        }

        $hasRemainingFees = Schema::hasTable('fee_groups_feetype')
            && DB::table('fee_groups_feetype')
                ->where('fee_class_group_id', $feeRecord->fee_class_group_id)
                ->exists();

        if (! $hasRemainingFees) {
            DB::table('fee_class_groups')
                ->where('id', $feeRecord->fee_class_group_id)
                ->where('brc_id', $branchId)
                ->delete();
        }
    }

    private function showMonthCount(int $branchId): bool
    {
        foreach (['sch_settings', 'setting', 'settings'] as $table) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'fee_mode_admission')) {
                continue;
            }

            $columns = Schema::getColumnListing($table);
            $mode = DB::table($table)
                ->when(in_array('brc_id', $columns, true), fn (Builder $query) => $query->where('brc_id', $branchId))
                ->value('fee_mode_admission');

            return in_array($mode, ['installments', 'both'], true);
        }

        return true;
    }

    private function currentSessionLabel(int $branchId): string
    {
        $sessionId = app(AcademicSessionContext::class)->id();

        foreach (['sessions', 'session', 'adcademicyear'] as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            $columns = Schema::getColumnListing($table);
            $nameColumn = collect(['session', 'name', 'year', 'session_name'])->first(fn (string $column): bool => in_array($column, $columns, true));

            if (! $nameColumn) {
                continue;
            }

            $value = DB::table($table)
                ->when($sessionId && in_array('id', $columns, true), fn (Builder $query) => $query->where('id', $sessionId))
                ->when(in_array('brc_id', $columns, true), fn (Builder $query) => $query->where('brc_id', $branchId))
                ->value($nameColumn);

            if ($value) {
                return (string) $value;
            }
        }

        return '2026-27';
    }

    /**
     * @param  array<int, string>  $wantedColumns
     * @return array<int, string>
     */
    private function selectExistingColumns(string $table, array $wantedColumns): array
    {
        $columns = Schema::getColumnListing($table);

        return array_values(array_intersect($wantedColumns, $columns));
    }

    /**
     * @param  array<int, string>  $columns
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function existingColumnsOnly(array $columns, array $payload): array
    {
        return array_intersect_key($payload, array_flip($columns));
    }
}
