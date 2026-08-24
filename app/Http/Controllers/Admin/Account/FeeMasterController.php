<?php

namespace App\Http\Controllers\Admin\Account;

use App\Models\Account\AccountHead;
use App\Models\Account\FeeClassGroup;
use App\Models\Account\FeeMaster;
use App\Models\Branch;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class FeeMasterController extends BaseAccountController
{
    protected bool $monthCountEnsured = false;

    /**
     * Ensure the month_count column exists in fee_groups_feetype.
     */
    protected function ensureMonthCountColumn(): void
    {
        if ($this->monthCountEnsured) {
            return;
        }

        if (Schema::hasTable('fee_groups_feetype')) {
            if (!Schema::hasColumn('fee_groups_feetype', 'month_count')) {
                DB::statement("ALTER TABLE `fee_groups_feetype` ADD COLUMN `month_count` INT(11) NOT NULL DEFAULT 0 AFTER `frequency`");
            }

            DB::statement("
                UPDATE `fee_groups_feetype`
                SET `month_count` = CASE
                    WHEN `month_count` > 0 THEN `month_count`
                    WHEN `frequency` = 'Monthly' THEN 12
                    WHEN `frequency` = 'Yearly' THEN 1
                    WHEN `frequency` = 'One Time' THEN 1
                    ELSE 0
                END
                WHERE `month_count` = 0 OR `month_count` IS NULL
            ");
        }

        $this->monthCountEnsured = true;
    }

    /**
     * Resolve the current branch ID.
     */
    protected function resolveBranchId(Request $request, ?int $branch_id = null): int
    {
        if ($branch_id && $branch_id > 0) {
            return $branch_id;
        }

        if ($request->filled('brc_id')) {
            return (int) $request->input('brc_id');
        }

        if ($request->hasSession()) {
            if ($request->session()->has('current_branch_id')) {
                return (int) $request->session()->get('current_branch_id');
            }

            if ($request->session()->has('brc_id')) {
                return (int) $request->session()->get('brc_id');
            }

            if ($request->session()->has('branch_id')) {
                return (int) $request->session()->get('branch_id');
            }
        }

        return 1;
    }

    /**
     * Get system settings & session name for branch.
     */
    protected function getBranchSettings(int $brc_id): object
    {
        $setting = null;
        if (Schema::hasTable('system_settings')) {
            $query = DB::table('system_settings')
                ->leftJoin('sessions', 'sessions.id', '=', 'system_settings.session_id')
                ->leftJoin('currencies', 'currencies.id', '=', 'system_settings.currency')
                ->where('system_settings.brc_id', $brc_id)
                ->select([
                    'system_settings.*',
                    'sessions.session as current_session_name',
                    'currencies.symbol as currency_symbol_text',
                ])
                ->first();

            if (!$query) {
                $query = DB::table('system_settings')
                    ->leftJoin('sessions', 'sessions.id', '=', 'system_settings.session_id')
                    ->leftJoin('currencies', 'currencies.id', '=', 'system_settings.currency')
                    ->select([
                        'system_settings.*',
                        'sessions.session as current_session_name',
                        'currencies.symbol as currency_symbol_text',
                    ])
                    ->first();
            }

            $setting = $query;
        }

        $sessionName = $setting->current_session_name ?? (date('Y') . '-' . substr(date('Y') + 1, 2));
        $currencySymbol = $setting->currency_symbol ?? $setting->currency_symbol_text ?? 'Rs.';
        $feeModeAdmission = $setting->fee_mode_admission ?? 'installments';

        return (object) [
            'raw' => $setting,
            'session_id' => $setting->session_id ?? 1,
            'session_name' => $sessionName,
            'currency_symbol' => $currencySymbol,
            'fee_mode_admission' => $feeModeAdmission,
        ];
    }

    /**
     * Display the Fee Structure Master list and Add Form.
     */
    public function index(Request $request, ?int $branch_id = null): View|RedirectResponse
    {
        $this->ensureMonthCountColumn();
        $brc_id = $this->resolveBranchId($request, $branch_id);
        $settings = $this->getBranchSettings($brc_id);

        // Handle POST submission for Adding Fee Structure
        if ($request->isMethod('post')) {
            return $this->store($request, $brc_id);
        }

        $branchlist = Schema::hasTable('branches')
            ? Branch::query()->where('is_active', 'yes')->orWhere('is_active', '1')->orderBy('id', 'asc')->get()
            : (Schema::hasTable('branch') ? DB::table('branch')->orderBy('id', 'asc')->get() : collect());

        $classlist = Schema::hasTable('classes')
            ? DB::table('classes')->orderBy('id', 'asc')->get()
            : collect();

        $feetypeList = Schema::hasTable('accountshead')
            ? DB::table('accountshead')
                ->where('new_accounts_id', 19)
                ->where(function ($query) use ($brc_id) {
                    $query->whereNull('brc_id')->orWhere('brc_id', $brc_id);
                })
                ->orderBy('id', 'asc')
                ->get()
            : collect();

        $feemasterList = $this->getFeesByClass(null, $brc_id);

        $show_month_count = !empty($settings->fee_mode_admission) && in_array($settings->fee_mode_admission, ['installments', 'both', 'normal']);

        return view('admin.account.feemaster.index', [
            'title' => 'Feemaster List',
            'brc_id' => $brc_id,
            'settings' => $settings,
            'current_session_name' => $settings->session_name,
            'currency_symbol' => $settings->currency_symbol,
            'show_month_count' => $show_month_count,
            'branchlist' => $branchlist,
            'classlist' => $classlist,
            'feetypeList' => $feetypeList,
            'feemasterList' => $feemasterList,
        ]);
    }

    /**
     * Store a new Fee Structure record.
     */
    public function store(Request $request, int $brc_id): RedirectResponse
    {
        $this->ensureMonthCountColumn();

        $validated = $request->validate([
            'class_id' => ['required', 'integer'],
            'feetype_id' => ['required', 'integer'],
            'frequency' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'min:0'],
            'month_count' => ['nullable', 'integer'],
            'description' => ['nullable', 'string', 'max:1000'],
            'brc_id' => ['nullable', 'integer'],
        ], [
            'class_id.required' => 'The Class field is required.',
            'feetype_id.required' => 'The Fee Type field is required.',
            'frequency.required' => 'The Frequency field is required.',
            'amount.required' => 'The Amount field is required.',
        ]);

        $postBrcId = $request->filled('brc_id') ? (int) $request->input('brc_id') : $brc_id;
        $classId = (int) $validated['class_id'];
        $feetypeId = (int) $validated['feetype_id'];
        $frequency = $validated['frequency'];
        $amount = (float) $validated['amount'];
        $monthCount = (int) ($validated['month_count'] ?? 0);
        $note = $validated['description'] ?? '';

        // Check if combination exists
        if ($this->checkExists($classId, $feetypeId, $postBrcId)) {
            return redirect()
                ->route('admin.account.fee-master.index', ['branch_id' => $postBrcId])
                ->withInput()
                ->with('error', 'Fee combination already exists for this class and branch.');
        }

        // Find or create parent fee_class_groups
        $parentGroupId = $this->getOrCreateClassGroupId($classId, $postBrcId);

        $settings = $this->getBranchSettings($postBrcId);

        DB::table('fee_groups_feetype')->insert([
            'brc_id' => $postBrcId,
            'fee_class_group_id' => $parentGroupId,
            'fee_class_id' => $classId,
            'feetype_id' => $feetypeId,
            'amount' => $amount,
            'frequency' => $frequency,
            'month_count' => $monthCount,
            'session_id' => $settings->session_id ?? null,
            'note' => $note,
            'is_active' => 'yes',
            'created_at' => now(),
        ]);

        return redirect()
            ->route('admin.account.fee-master.index', ['branch_id' => $postBrcId])
            ->with('success', 'Fee Structure added successfully');
    }

    /**
     * Edit form for Fee Structure.
     */
    public function edit(Request $request, int $id, ?int $branch_id = null): View|RedirectResponse
    {
        $this->ensureMonthCountColumn();
        $brc_id = $this->resolveBranchId($request, $branch_id);
        $settings = $this->getBranchSettings($brc_id);

        $feemaster = DB::table('fee_groups_feetype')
            ->leftJoin('accountshead', 'accountshead.id', '=', 'fee_groups_feetype.feetype_id')
            ->where('fee_groups_feetype.id', $id)
            ->select('fee_groups_feetype.*', 'accountshead.name as type')
            ->first();

        if (!$feemaster) {
            return redirect()
                ->route('admin.account.feemaster.index', ['branch_id' => $brc_id])
                ->with('error', 'Fee Structure record not found.');
        }

        if (!empty($feemaster->brc_id)) {
            $brc_id = (int) $feemaster->brc_id;
            $settings = $this->getBranchSettings($brc_id);
        }

        $branchlist = Schema::hasTable('branches')
            ? Branch::query()->where('is_active', 'yes')->orWhere('is_active', '1')->orderBy('id', 'asc')->get()
            : (Schema::hasTable('branch') ? DB::table('branch')->orderBy('id', 'asc')->get() : collect());

        $classlist = Schema::hasTable('classes')
            ? DB::table('classes')->orderBy('id', 'asc')->get()
            : collect();

        $feetypeList = Schema::hasTable('accountshead')
            ? DB::table('accountshead')
                ->where('new_accounts_id', 19)
                ->where(function ($query) use ($brc_id) {
                    $query->whereNull('brc_id')->orWhere('brc_id', $brc_id);
                })
                ->orderBy('id', 'asc')
                ->get()
            : collect();

        $feemasterList = $this->getFeesByClass(null, $brc_id);

        $show_month_count = !empty($settings->fee_mode_admission) && in_array($settings->fee_mode_admission, ['installments', 'both', 'normal']);

        $edit_month_count = !empty($feemaster->month_count) ? (int) $feemaster->month_count : 0;
        $edit_base_amount = (float) $feemaster->amount;
        if ($show_month_count && $edit_month_count > 0) {
            $edit_base_amount = $edit_base_amount / $edit_month_count;
        }

        return view('admin.account.feemaster.edit', [
            'title' => 'Edit Fee Structure',
            'id' => $id,
            'brc_id' => $brc_id,
            'settings' => $settings,
            'current_session_name' => $settings->session_name,
            'currency_symbol' => $settings->currency_symbol,
            'show_month_count' => $show_month_count,
            'feemaster' => $feemaster,
            'edit_base_amount' => $edit_base_amount,
            'edit_month_count' => $edit_month_count,
            'branchlist' => $branchlist,
            'classlist' => $classlist,
            'feetypeList' => $feetypeList,
            'feemasterList' => $feemasterList,
        ]);
    }

    /**
     * Update an existing Fee Structure record.
     */
    public function update(Request $request, int $id, ?int $branch_id = null): RedirectResponse
    {
        $this->ensureMonthCountColumn();
        $brc_id = $this->resolveBranchId($request, $branch_id);

        $validated = $request->validate([
            'class_id' => ['required', 'integer'],
            'feetype_id' => ['required', 'integer'],
            'frequency' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'min:0'],
            'month_count' => ['nullable', 'integer'],
            'description' => ['nullable', 'string', 'max:1000'],
            'brc_id' => ['nullable', 'integer'],
        ], [
            'class_id.required' => 'The Class field is required.',
            'feetype_id.required' => 'The Fee Type field is required.',
            'frequency.required' => 'The Frequency field is required.',
            'amount.required' => 'The Amount field is required.',
        ]);

        $postBrcId = $request->filled('brc_id') ? (int) $request->input('brc_id') : $brc_id;
        $classId = (int) $validated['class_id'];
        $feetypeId = (int) $validated['feetype_id'];
        $frequency = $validated['frequency'];
        $amount = (float) $validated['amount'];
        $monthCount = (int) ($validated['month_count'] ?? 0);
        $note = $validated['description'] ?? '';

        // Check duplicate excluding current ID
        if ($this->checkExists($classId, $feetypeId, $postBrcId, $id)) {
            return redirect()
                ->route('admin.account.feemaster.edit', ['id' => $id, 'branch_id' => $postBrcId])
                ->withInput()
                ->with('error', 'Fee combination already exists for this class and branch.');
        }

        $parentGroupId = $this->getOrCreateClassGroupId($classId, $postBrcId);

        DB::table('fee_groups_feetype')
            ->where('id', $id)
            ->update([
                'brc_id' => $postBrcId,
                'fee_class_group_id' => $parentGroupId,
                'fee_class_id' => $classId,
                'feetype_id' => $feetypeId,
                'amount' => $amount,
                'frequency' => $frequency,
                'month_count' => $monthCount,
                'note' => $note,
            ]);

        return redirect()
            ->route('admin.account.feemaster.index', ['branch_id' => $postBrcId])
            ->with('success', 'Fees Master updated successfully');
    }

    /**
     * Delete a Fee Structure item.
     */
    public function destroy(Request $request, int $id, ?int $branch_id = null): RedirectResponse
    {
        $brc_id = $this->resolveBranchId($request, $branch_id);

        $feeType = DB::table('fee_groups_feetype')
            ->where('id', $id)
            ->first();

        if ($feeType) {
            $classGroupId = $feeType->fee_class_group_id;
            $itemBrcId = $feeType->brc_id;

            // Delete the fee type
            DB::table('fee_groups_feetype')->where('id', $id)->delete();

            // If no more feetypes exist under this group, delete the group
            $remaining = DB::table('fee_groups_feetype')
                ->where('fee_class_group_id', $classGroupId)
                ->count();

            if ($remaining === 0 && $classGroupId) {
                DB::table('fee_class_groups')->where('id', $classGroupId)->delete();
            }

            return redirect()
                ->route('admin.account.fee-master.index', ['branch_id' => $itemBrcId])
                ->with('success', 'Fee Structure deleted successfully');
        }

        return redirect()
            ->route('admin.account.fee-master.index', ['branch_id' => $brc_id])
            ->with('error', 'Record not found.');
    }

    /**
     * Download Fee Structure PDF report directly.
     */
    public function downloadPdf(Request $request, ?int $branch_id = null)
    {
        $this->ensureMonthCountColumn();
        $brc_id = $this->resolveBranchId($request, $branch_id);

        $settinglist = $this->getBranchSettings($brc_id);
        $branchTable = Schema::hasTable('branches') ? 'branches' : 'branch';
        $branch = DB::table($branchTable)->where('id', $brc_id)->first() ?? (object) ['name' => 'Main Campus'];

        $feemasterList = $this->getFeesByClass(null, $brc_id);

        // Find logo image
        $logoPath = null;
        if (!empty($settinglist->image)) {
            $possible = [
                base_path('../cmsc/uploads/system_content/logo/' . $settinglist->image),
                public_path('uploads/system_content/logo/' . $settinglist->image),
                base_path('../cmsc/assets/images/' . $settinglist->image),
                public_path('assets/images/' . $settinglist->image),
            ];
            foreach ($possible as $p) {
                if (file_exists($p)) {
                    $logoPath = $p;
                    break;
                }
            }
        }

        $logoBase64 = null;
        if ($logoPath && file_exists($logoPath)) {
            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
            $data = file_get_contents($logoPath);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        if ($request->has('print') || $request->has('preview')) {
            return view('admin.reports.pdffeestructurereport', [
                'settinglist' => $settinglist,
                'branch' => $branch,
                'feemasterList' => $feemasterList,
                'logoBase64' => $logoBase64,
                'autoPrint' => $request->has('print'),
            ]);
        }

        $pdf = Pdf::loadView('admin.reports.pdffeestructurereport', [
            'settinglist' => $settinglist,
            'branch' => $branch,
            'feemasterList' => $feemasterList,
            'logoBase64' => $logoBase64,
        ])->setPaper('a4', 'portrait');

        $fileName = 'fee_structure_report_' . date('Ymd_His') . '.pdf';

        return $pdf->download($fileName);
    }

    /**
     * Fetch fee class groups with fee types.
     */
    protected function getFeesByClass(?int $groupId = null, ?int $brc_id = null): array
    {
        $branchTable = Schema::hasTable('branches') ? 'branches' : 'branch';

        $query = DB::table('fee_class_groups')
            ->leftJoin($branchTable, $branchTable . '.id', '=', 'fee_class_groups.brc_id')
            ->leftJoin('classes', 'classes.id', '=', 'fee_class_groups.fee_class_id')
            ->select([
                'fee_class_groups.*',
                'classes.class as class_name',
                DB::raw("COALESCE({$branchTable}.name, 'Main Campus') as branch_name"),
            ]);

        if ($groupId) {
            $query->where('fee_class_groups.id', $groupId);
        }

        if ($brc_id) {
            $query->where('fee_class_groups.brc_id', $brc_id);
        }

        $result = $query->orderBy('fee_class_groups.id', 'asc')->get()->toArray();

        foreach ($result as $value) {
            $value->feetypes = DB::table('fee_groups_feetype')
                ->join('accountshead', 'accountshead.id', '=', 'fee_groups_feetype.feetype_id')
                ->where('fee_groups_feetype.fee_class_id', $value->fee_class_id)
                ->where('fee_groups_feetype.fee_class_group_id', $value->id)
                ->where('fee_groups_feetype.brc_id', $value->brc_id)
                ->select([
                    'fee_groups_feetype.*',
                    'accountshead.name as type',
                ])
                ->orderBy('fee_groups_feetype.id', 'asc')
                ->get()
                ->toArray();
        }

        return $result;
    }

    /**
     * Get or create fee_class_groups ID.
     */
    protected function getOrCreateClassGroupId(int $classId, int $brc_id): int
    {
        $existing = DB::table('fee_class_groups')
            ->where('brc_id', $brc_id)
            ->where('fee_class_id', $classId)
            ->first();

        if ($existing) {
            return (int) $existing->id;
        }

        return (int) DB::table('fee_class_groups')->insertGetId([
            'brc_id' => $brc_id,
            'fee_class_id' => $classId,
            'session_id' => $this->getBranchSettings($brc_id)->session_id ?? null,
            'is_active' => 'yes',
            'created_at' => now(),
        ]);
    }

    /**
     * Check if a fee combination already exists.
     */
    protected function checkExists(int $classId, int $feetypeId, int $brc_id, ?int $excludeId = null): bool
    {
        $query = DB::table('fee_groups_feetype')
            ->where('fee_class_id', $classId)
            ->where('feetype_id', $feetypeId)
            ->where('brc_id', $brc_id);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
