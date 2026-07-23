<?php

namespace App\Http\Controllers\Admin\Account;

use App\Services\AcademicSessionContext;
use App\Services\BranchContext;
use Carbon\CarbonImmutable;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AccountController extends BaseAccountController
{
    public function index(Request $request): View
    {
        $branchId = $this->selectedBranchId();

        return view('admin.account.coa.chart-of-accounts', [
            'accountTypes' => $this->accountTypes(),
            'chartRows' => $this->chartRows($branchId),
            'hierarchy' => $this->accountHierarchy($branchId),
        ]);
    }

    public function newAccounts(): View
    {
        return $this->newAccountsView();
    }

    public function editNewAccount(int $account): View
    {
        return $this->newAccountsView($account);
    }

    public function storeNewAccount(Request $request): RedirectResponse
    {
        return $this->saveNewAccount($request);
    }

    public function updateNewAccount(Request $request, int $account): RedirectResponse
    {
        return $this->saveNewAccount($request, $account);
    }

    public function accountsHead(?int $branch = null): View
    {
        return $this->accountsHeadView($branch);
    }

    public function editAccountsHead(int $account, ?int $branch = null): View
    {
        return $this->accountsHeadView($branch, $account);
    }

    public function storeAccountsHead(Request $request, ?int $branch = null): RedirectResponse
    {
        return $this->saveAccountsHead($request, $branch);
    }

    public function updateAccountsHead(Request $request, int $account, ?int $branch = null): RedirectResponse
    {
        return $this->saveAccountsHead($request, $branch, $account);
    }

    public function getByNewAccounts(Request $request): JsonResponse
    {
        $headId = $request->integer('accounts_head_id');

        return response()->json($headId ? $this->newAccountsForType($headId) : []);
    }

    public function changeStatus(Request $request): JsonResponse
    {
        return $this->toggleAccountHeadColumn($request, 'is_active', ['yes', 'no']);
    }

    public function changeStatusPost(Request $request): JsonResponse
    {
        return $this->toggleAccountHeadColumn($request, 'is_posted', [1, 0]);
    }

    public function dashboard(Request $request): View
    {
        $today = CarbonImmutable::now();
        $monthStart = $today->startOfMonth();
        $monthEnd = $today->endOfMonth();
        $days = range(1, $monthEnd->day);
        $branchId = app(BranchContext::class)->id();
        $sessionId = app(AcademicSessionContext::class)->id();

        $dailyCollections = $this->dailyAmounts(
            'student_fees_deposite_details',
            'date',
            'paid_amount',
            $monthStart,
            $monthEnd,
            $branchId,
            $sessionId,
        );
        $dailyExpenses = $this->dailyAmounts(
            'expenses_bill',
            'date',
            'grand_total',
            $monthStart,
            $monthEnd,
            $branchId,
            $sessionId,
        );

        $feeOverview = $this->feeOverview($today, $branchId, $sessionId);
        $expenseOverview = $this->expenseOverview($today, $monthStart, $monthEnd, $branchId, $sessionId);

        return view('admin.account.dashboard', [
            'branchId' => $branchId,
            'currentMonthShort' => $today->format('M Y'),
            'currentMonthLong' => $today->format('F Y'),
            'days' => array_map(fn (int $day): string => str_pad((string) $day, 2, '0', STR_PAD_LEFT), $days),
            'dailyCollections' => array_map(fn (int $day): float => $dailyCollections[$day] ?? 0.0, $days),
            'dailyExpenses' => array_map(fn (int $day): float => $dailyExpenses[$day] ?? 0.0, $days),
            'feeOverview' => $feeOverview,
            'expenseOverview' => $expenseOverview,
        ]);
    }

    /**
     * @return array<int, float>
     */
    private function dailyAmounts(
        string $table,
        string $dateColumn,
        string $amountColumn,
        CarbonImmutable $start,
        CarbonImmutable $end,
        ?int $branchId,
        ?int $sessionId,
    ): array {
        if (! Schema::hasTable($table)) {
            return [];
        }

        $columns = Schema::getColumnListing($table);

        if (! in_array($dateColumn, $columns, true) || ! in_array($amountColumn, $columns, true)) {
            return [];
        }

        return DB::table($table)
            ->selectRaw("DAY(`{$dateColumn}`) as day_number, COALESCE(SUM(`{$amountColumn}`), 0) as total_amount")
            ->whereBetween($dateColumn, [$start->toDateString(), $end->toDateString()])
            ->when($branchId && in_array('brc_id', $columns, true), fn (Builder $query) => $query->where('brc_id', $branchId))
            ->when($sessionId && in_array('session_id', $columns, true), fn (Builder $query) => $query->where('session_id', $sessionId))
            ->groupBy('day_number')
            ->pluck('total_amount', 'day_number')
            ->mapWithKeys(fn (mixed $total, mixed $day): array => [(int) $day => (float) $total])
            ->all();
    }

    /**
     * @return array<string, float|int>
     */
    private function feeOverview(CarbonImmutable $today, ?int $branchId, ?int $sessionId): array
    {
        $empty = [
            'receivable' => 0.0,
            'collection' => 0.0,
            'waive_off' => 0.0,
            'balance' => 0.0,
            'today_collection' => 0.0,
            'total_paid' => 0,
            'paid_progress' => 0.0,
            'total_unpaid' => 0,
            'unpaid_progress' => 0.0,
            'total_partial' => 0,
            'partial_progress' => 0.0,
            'total_free' => 0,
            'free_progress' => 0.0,
        ];

        if (! Schema::hasTable('student_fees_deposite_details')) {
            return $empty;
        }

        $columns = Schema::getColumnListing('student_fees_deposite_details');

        if (! in_array('amount', $columns, true)) {
            return $empty;
        }

        $query = DB::table('student_fees_deposite_details')
            ->when($branchId && in_array('brc_id', $columns, true), fn (Builder $query) => $query->where('brc_id', $branchId))
            ->when($sessionId && in_array('session_id', $columns, true), fn (Builder $query) => $query->where('session_id', $sessionId));

        if (in_array('fee_month', $columns, true)) {
            $query->where('fee_month', 'like', $today->format('Y-m').'%');
        } elseif (in_array('fee_month_date', $columns, true)) {
            $query->whereBetween('fee_month_date', [$today->startOfMonth()->toDateString(), $today->endOfMonth()->toDateString()]);
        } elseif (in_array('date', $columns, true)) {
            $query->whereBetween('date', [$today->startOfMonth()->toDateString(), $today->endOfMonth()->toDateString()]);
        }

        $records = $query->get();
        $totalFees = $records->count();
        $paidRecords = $records->filter(fn (object $record): bool => (float) ($record->paid_amount ?? 0) > 0);
        $waiveOffRecords = in_array('paid_types', $columns, true)
            ? $records->filter(fn (object $record): bool => (string) ($record->paid_types ?? '') === 'waive_off')
            : collect();

        $receivable = (float) $records->sum(fn (object $record): float => (float) ($record->amount ?? 0));
        $collection = in_array('paid_amount', $columns, true)
            ? (float) $paidRecords->sum(fn (object $record): float => (float) ($record->paid_amount ?? 0))
            : 0.0;
        $waiveOff = (float) $waiveOffRecords->sum(fn (object $record): float => (float) ($record->paid_amount ?? 0));
        $totalPartial = $waiveOffRecords->count();
        $totalPaid = $paidRecords->count();
        $totalUnpaid = max($totalFees - $totalPaid - $totalPartial, 0);

        $todayCollection = in_array('date', $columns, true) && in_array('paid_amount', $columns, true)
            ? (float) DB::table('student_fees_deposite_details')
                ->whereDate('date', $today->toDateString())
                ->when($branchId && in_array('brc_id', $columns, true), fn (Builder $query) => $query->where('brc_id', $branchId))
                ->when($sessionId && in_array('session_id', $columns, true), fn (Builder $query) => $query->where('session_id', $sessionId))
                ->sum('paid_amount')
            : 0.0;

        return [
            'receivable' => $receivable,
            'collection' => $collection,
            'waive_off' => $waiveOff,
            'balance' => $receivable - $collection,
            'today_collection' => $todayCollection,
            'total_paid' => $totalPaid,
            'paid_progress' => $this->percentage($totalPaid, $totalFees),
            'total_unpaid' => $totalUnpaid,
            'unpaid_progress' => $this->percentage($totalUnpaid, $totalFees),
            'total_partial' => $totalPartial,
            'partial_progress' => $this->percentage($totalPartial, $totalFees),
            'total_free' => 0,
            'free_progress' => 0.0,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function expenseOverview(
        CarbonImmutable $today,
        CarbonImmutable $start,
        CarbonImmutable $end,
        ?int $branchId,
        ?int $sessionId,
    ): array {
        $empty = [
            'month_total' => 0.0,
            'today_total' => 0.0,
            'head_labels' => [],
            'head_totals' => [],
        ];

        if (! Schema::hasTable('expenses_bill')) {
            return $empty;
        }

        $columns = Schema::getColumnListing('expenses_bill');

        if (! in_array('date', $columns, true) || ! in_array('grand_total', $columns, true)) {
            return $empty;
        }

        $baseQuery = DB::table('expenses_bill')
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->when($branchId && in_array('brc_id', $columns, true), fn (Builder $query) => $query->where('brc_id', $branchId))
            ->when($sessionId && in_array('session_id', $columns, true), fn (Builder $query) => $query->where('session_id', $sessionId));

        $monthTotal = (float) (clone $baseQuery)->sum('grand_total');
        $todayTotal = (float) (clone $baseQuery)->whereDate('date', $today->toDateString())->sum('grand_total');

        $labelColumn = collect(['paid_to', 'name', 'note'])
            ->first(fn (string $column): bool => in_array($column, $columns, true));

        $heads = $labelColumn
            ? (clone $baseQuery)
                ->selectRaw("COALESCE(`{$labelColumn}`, 'Expenses') as label, COALESCE(SUM(`grand_total`), 0) as total")
                ->groupBy('label')
                ->orderByDesc('total')
                ->limit(8)
                ->get()
            : collect();

        return [
            'month_total' => $monthTotal,
            'today_total' => $todayTotal,
            'head_labels' => $heads->pluck('label')->values()->all(),
            'head_totals' => $heads->pluck('total')->map(fn (mixed $total): float => (float) $total)->values()->all(),
        ];
    }

    private function percentage(int $value, int $total): float
    {
        if ($total <= 0) {
            return 0.0;
        }

        return ($value * 100) / $total;
    }

    private function newAccountsView(?int $accountId = null): View
    {
        $account = $accountId && Schema::hasTable('accountsnew')
            ? DB::table('accountsnew')->where('id', $accountId)->first()
            : null;

        return view('admin.account.coa.newaccounts', [
            'title' => $account ? 'Edit Accounts Type' : 'Add Accounts Type',
            'account' => $account,
            'accountTypes' => $this->activeAccountTypes(),
            'hierarchy' => $this->accountTypeHierarchy(),
        ]);
    }

    private function accountsHeadView(?int $branchId = null, ?int $accountId = null): View
    {
        $selectedBranchId = $branchId ?: $this->selectedBranchId();
        $account = $accountId && Schema::hasTable('accountshead')
            ? DB::table('accountshead')->where('id', $accountId)->first()
            : null;
        $openingBalance = $accountId && Schema::hasTable('opening_balances')
            ? DB::table('opening_balances')->where('brc_id', $selectedBranchId)->where('acc_head_id', $accountId)->first()
            : null;

        return view('admin.account.coa.accountshead', [
            'title' => $account ? 'Edit Accounts Head' : 'Add Accounts Head',
            'branchId' => $selectedBranchId,
            'branches' => $this->branches(),
            'staffList' => $this->staffList($selectedBranchId),
            'account' => $account,
            'openingBalance' => $openingBalance,
            'accountTypes' => $this->activeAccountTypes(),
            'newAccounts' => $account ? $this->newAccountsForType((int) $account->accounts_head_id) : [],
            'hierarchy' => $this->accountHierarchy($selectedBranchId),
        ]);
    }

    private function saveNewAccount(Request $request, ?int $accountId = null): RedirectResponse
    {
        abort_unless(Schema::hasTable('accountsnew'), 404);

        $validated = $request->validate([
            'accounts_type_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $exists = DB::table('accountsnew')
            ->where('accounts_type_id', $validated['accounts_type_id'])
            ->where('name', $validated['name'])
            ->when($accountId, fn (Builder $query) => $query->where('id', '!=', $accountId))
            ->exists();

        if ($exists) {
            return back()->withErrors(['name' => 'Record already exists'])->withInput();
        }

        $data = [
            'accounts_type_id' => $validated['accounts_type_id'],
            'name' => $validated['name'],
            'note' => $validated['description'] ?? null,
        ];

        if (! $accountId) {
            $data['code'] = $this->nextNewAccountCode((int) $validated['accounts_type_id']);
            DB::table('accountsnew')->insert($data);
            $message = 'New Accounts added successfully';
        } else {
            DB::table('accountsnew')->where('id', $accountId)->update($data);
            $message = 'New Accounts updated successfully';
        }

        return redirect()->route('admin.account.accounts.newaccounts')->with('success', $message);
    }

    private function saveAccountsHead(Request $request, ?int $branchId = null, ?int $accountId = null): RedirectResponse
    {
        abort_unless(Schema::hasTable('accountshead'), 404);

        $selectedBranchId = $request->integer('brc_id') ?: $branchId ?: $this->selectedBranchId();
        $validated = $request->validate([
            'accounts_head_id' => ['required', 'integer'],
            'account_type_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'staff_id' => ['nullable', 'integer'],
            'date' => ['nullable', 'date'],
            'opening_balance_amount' => ['nullable', 'numeric'],
            'description' => ['nullable', 'string'],
        ]);

        $exists = DB::table('accountshead')
            ->where('name', $validated['name'])
            ->where('accounts_head_id', $validated['accounts_head_id'])
            ->where('new_accounts_id', $validated['account_type_id'])
            ->where(function (Builder $query) use ($selectedBranchId): void {
                $query->whereNull('brc_id')->orWhere('brc_id', $selectedBranchId);
            })
            ->when($accountId, fn (Builder $query) => $query->where('id', '!=', $accountId))
            ->exists();

        if ($exists) {
            return back()->withErrors(['name' => 'Record already exists'])->withInput();
        }

        $data = [
            'accounts_head_id' => $validated['accounts_head_id'],
            'new_accounts_id' => $validated['account_type_id'],
            'staff_id' => $validated['staff_id'] ?? null,
            'name' => $validated['name'],
            'note' => $validated['description'] ?? null,
        ];

        if (! $accountId) {
            $data['code'] = $this->nextAccountHeadCode((int) $validated['account_type_id']);
            $data['brc_id'] = $selectedBranchId;
            $accountId = (int) DB::table('accountshead')->insertGetId($data);
            $message = 'Accounts Head added successfully';
        } else {
            DB::table('accountshead')->where('id', $accountId)->update($data);
            $message = 'Accounts Head updated successfully';
        }

        $this->saveOpeningBalance($validated, $selectedBranchId, $accountId);

        return redirect()->route('admin.account.accounts.accountshead', ['branch' => $selectedBranchId])->with('success', $message);
    }

    private function saveOpeningBalance(array $validated, int $branchId, int $accountId): void
    {
        if (! Schema::hasTable('opening_balances') || empty($validated['opening_balance_amount'])) {
            return;
        }

        $amount = (float) $validated['opening_balance_amount'];
        $isDebit = (int) $validated['accounts_head_id'] === 1;
        $data = [
            'brc_id' => $branchId,
            'par_acc_head_id' => 21,
            'acc_head_id' => $accountId,
            'date' => $validated['date'] ?? now()->toDateString(),
            'debit_amount' => $isDebit ? $amount : 0,
            'credit_amount' => $isDebit ? 0 : $amount,
            'note' => $validated['description'] ?? null,
        ];

        $existingId = DB::table('opening_balances')
            ->where('brc_id', $branchId)
            ->where('acc_head_id', $accountId)
            ->value('id');

        if ($existingId) {
            DB::table('opening_balances')->where('id', $existingId)->update($data);

            return;
        }

        DB::table('opening_balances')->insert($data);
    }

    private function toggleAccountHeadColumn(Request $request, string $column, array $values): JsonResponse
    {
        if (! Schema::hasTable('accountshead') || ! Schema::hasColumn('accountshead', $column)) {
            return response()->json(['status' => 'fail', 'error' => ['Accounts table is not available.']], 404);
        }

        $id = $request->integer('id');
        $current = DB::table('accountshead')->where('id', $id)->value($column);

        if ($current === null) {
            return response()->json(['status' => 'fail', 'error' => ['Account record was not found.']], 404);
        }

        DB::table('accountshead')->where('id', $id)->update([
            $column => (string) $current === (string) $values[0] ? $values[1] : $values[0],
        ]);

        return response()->json(['status' => 'success', 'message' => 'Record updated successfully']);
    }

    private function nextNewAccountCode(int $accountTypeId): string
    {
        $count = Schema::hasTable('accountsnew')
            ? DB::table('accountsnew')->where('accounts_type_id', $accountTypeId)->count()
            : 0;

        return (string) ($accountTypeId.($count + 1));
    }

    private function nextAccountHeadCode(int $newAccountId): string
    {
        $newAccount = Schema::hasTable('accountsnew')
            ? DB::table('accountsnew')->where('id', $newAccountId)->first()
            : null;
        $count = Schema::hasTable('accountshead')
            ? DB::table('accountshead')->where('new_accounts_id', $newAccountId)->count()
            : 0;

        return (string) (($newAccount->code ?? $newAccountId).($count + 1));
    }

    private function selectedBranchId(): int
    {
        return app(BranchContext::class)->id() ?: 1;
    }

    private function accountTypes(bool $activeOnly = false): array
    {
        if (! Schema::hasTable('accounts_type')) {
            return [];
        }

        $columns = Schema::getColumnListing('accounts_type');

        return DB::table('accounts_type')
            ->when($activeOnly && in_array('is_active', $columns, true), fn (Builder $query) => $query->where('is_active', 'no'))
            ->orderBy('id')
            ->get()
            ->all();
    }

    private function activeAccountTypes(): array
    {
        return $this->accountTypes(true);
    }

    private function accountTypeHierarchy(): array
    {
        return array_map(function (object $type): object {
            $type->newaccounts = $this->newAccountsForType((int) $type->id);

            return $type;
        }, $this->accountTypes());
    }

    private function accountHierarchy(int $branchId): array
    {
        return array_map(function (object $type) use ($branchId): object {
            $type->newaccounts = array_map(function (object $newAccount) use ($branchId): object {
                $newAccount->accountshead = $this->accountHeadsForNewAccount((int) $newAccount->id, $branchId);

                return $newAccount;
            }, $this->newAccountsForType((int) $type->id));

            return $type;
        }, $this->accountTypes());
    }

    private function newAccountsForType(int $accountTypeId): array
    {
        if (! Schema::hasTable('accountsnew')) {
            return [];
        }

        return DB::table('accountsnew')
            ->where('accounts_type_id', $accountTypeId)
            ->orderBy('id')
            ->get()
            ->all();
    }

    private function accountHeadsForNewAccount(int $newAccountId, int $branchId): array
    {
        if (! Schema::hasTable('accountshead')) {
            return [];
        }

        return DB::table('accountshead')
            ->where('new_accounts_id', $newAccountId)
            ->where(function (Builder $query) use ($branchId): void {
                $query->whereNull('brc_id')->orWhere('brc_id', $branchId);
            })
            ->orderBy('id')
            ->get()
            ->all();
    }

    private function chartRows(int $branchId): array
    {
        if (! Schema::hasTable('accounts_type') || ! Schema::hasTable('accountsnew') || ! Schema::hasTable('accountshead')) {
            return [];
        }

        return DB::table('accounts_type')
            ->join('accountsnew', 'accountsnew.accounts_type_id', '=', 'accounts_type.id')
            ->join('accountshead', 'accountshead.new_accounts_id', '=', 'accountsnew.id')
            ->where(function (Builder $query) use ($branchId): void {
                $query->whereNull('accountshead.brc_id')->orWhere('accountshead.brc_id', $branchId);
            })
            ->orderBy('accounts_type.id')
            ->get([
                'accounts_type.name as account_head',
                'accountsnew.name as account_type',
                'accountshead.code as account_code',
                'accountshead.name as account_name',
            ])
            ->all();
    }

    private function branches(): array
    {
        if (! Schema::hasTable('branch')) {
            return [];
        }

        return DB::table('branch')->orderBy('id')->get(['id', 'name'])->all();
    }

    private function staffList(int $branchId): array
    {
        if (! Schema::hasTable('staff')) {
            return [];
        }

        $columns = Schema::getColumnListing('staff');
        $select = array_values(array_intersect(['id', 'staff_id', 'employee_id', 'name', 'surname'], $columns));

        if ($select === []) {
            return [];
        }

        return DB::table('staff')
            ->when(in_array('brc_id', $columns, true), fn (Builder $query) => $query->where('brc_id', $branchId))
            ->orderBy('id')
            ->get($select)
            ->all();
    }
}
