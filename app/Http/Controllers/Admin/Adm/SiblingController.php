<?php

namespace App\Http\Controllers\Admin\Adm;

use App\Http\Requests\Admin\Adm\SiblingRequest;
use App\Models\Adm\Sibling;
use App\Services\AcademicSessionContext;
use App\Services\BranchContext;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class SiblingController extends BaseAdmController
{
    public function index(Request $request): View
    {
        $branchId = $request->integer('brc_id') ?: app(BranchContext::class)->id();
        $sessionId = app(AcademicSessionContext::class)->id();

        return view('admin.adm.siblings.index', [
            'branches' => Schema::hasTable('branch') ? DB::table('branch')->orderBy('name')->get(['id', 'name']) : collect(),
            'students' => $this->students($branchId, $sessionId),
            'siblings' => $this->siblings($request, $branchId, $sessionId),
            'nextCode' => $branchId ? ((int) Sibling::query()->where('brc_id', $branchId)->max('sibling_code')) + 1 : 1,
            'branchId' => $branchId,
        ]);
    }

    public function store(SiblingRequest $request): RedirectResponse
    {
        $sibling = DB::transaction(fn (): Sibling => $this->saveSibling($request));

        return redirect()->route('admin.adm.siblings.index', ['brc_id' => $sibling->brc_id])->with('status', 'Sibling added successfully.');
    }

    public function update(SiblingRequest $request, Sibling $sibling): JsonResponse
    {
        $this->authorizeSiblingBranch($request, $sibling);
        DB::transaction(fn (): Sibling => $this->saveSibling($request, $sibling));

        return response()->json(['status' => 'success', 'msg' => 'Sibling updated successfully.']);
    }

    public function destroy(Request $request, Sibling $sibling): RedirectResponse
    {
        $this->authorizeSiblingBranch($request, $sibling);
        $hasStudents = DB::table('student_session')->where('student_sibling_id', $sibling->id)->where('brc_id', $sibling->brc_id)->exists();

        if ($hasStudents) {
            return back()->with('error', 'Record not deleted because students are still attached to this sibling.');
        }

        DB::transaction(function () use ($sibling): void {
            DB::table('users')->where('sibling_id', $sibling->id)->where('role', 'parent')->delete();
            $sibling->delete();
        });

        return back()->with('status', 'Record deleted successfully.');
    }

    /** @return LengthAwarePaginator<int, object> */
    private function siblings(Request $request, ?int $branchId, ?int $sessionId): LengthAwarePaginator
    {
        if (! Schema::hasTable('student_sibling')) {
            return new LengthAwarePaginator([], 0, 10, 1, ['path' => $request->url(), 'query' => $request->query()]);
        }

        $query = Sibling::query()->when($branchId, fn ($query) => $query->where('brc_id', $branchId))->when($request->filled('search'), function ($query) use ($request): void {
            $term = $request->string('search')->toString();
            $query->where(function ($query) use ($term): void {
                $query->where('sibling_code', 'like', "%{$term}%")->orWhere('sibling_name', 'like', "%{$term}%")->orWhere('sibling_cnic', 'like', "%{$term}%")->orWhere('sibling_phone', 'like', "%{$term}%");
            });
        })->latest('id');

        $paginated = $query->paginate(10)->withQueryString();
        $ids = $paginated->getCollection()->pluck('id');
        $students = $this->studentRows($ids, $sessionId);
        $logins = Schema::hasTable('users') ? DB::table('users')->whereIn('sibling_id', $ids)->where('role', 'parent')->get()->keyBy('sibling_id') : collect();

        $paginated->setCollection($paginated->getCollection()->map(function (Sibling $sibling) use ($students, $logins): Sibling {
            $members = $students->get($sibling->id, collect());
            $sibling->setAttribute('members', $members);
            $sibling->setAttribute('member_count', $members->count());
            $sibling->setAttribute('login', $logins->get($sibling->id));

            return $sibling;
        }));

        return $paginated;
    }

    private function saveSibling(SiblingRequest $request, ?Sibling $sibling = null): Sibling
    {
        $data = $request->validated();
        $branchId = (int) ($data['brc_id'] ?? $sibling?->brc_id ?? app(BranchContext::class)->id() ?? DB::table('branch')->value('id'));
        $sibling ??= new Sibling;
        $sibling->fill(['brc_id' => $branchId, 'sibling_code' => $data['sibling_code'], 'sibling_name' => $data['sibling_name'], 'sibling_cnic' => $data['sibling_cnic'], 'sibling_phone' => $data['sibling_phone'], 'created_by' => auth()->id(), 'updated_by' => auth()->id(), 'is_active' => 'yes'])->save();

        $parentId = $this->parentLogin($sibling);
        $sessionIds = collect($data['student_session_ids'] ?? [])->map(fn ($id): int => (int) $id)->unique();
        $this->attachSessions($sessionIds, $sibling, $parentId);

        if ($request->filled('remove_student_session_ids')) {
            DB::table('student_session')->whereIn('id', $request->input('remove_student_session_ids', []))->where('student_sibling_id', $sibling->id)->update(['student_sibling_id' => 0]);
            DB::table('students')->whereIn('id', DB::table('student_session')->whereIn('id', $request->input('remove_student_session_ids', []))->pluck('student_id'))->update(['student_sibling_id' => 0, 'parent_id' => 0]);
        }

        return $sibling;
    }

    private function parentLogin(Sibling $sibling): int
    {
        $existing = DB::table('users')->where('sibling_id', $sibling->id)->where('role', 'parent')->value('id');
        if ($existing) {
            return (int) $existing;
        }

        return (int) DB::table('users')->insertGetId(['brc_id' => $sibling->brc_id, 'user_id' => 0, 'sibling_id' => $sibling->id, 'username' => 'parent'.$sibling->id, 'password' => (string) random_int(100000, 999999), 'childs' => 1, 'role' => 'parent', 'lang_id' => 4, 'currency_id' => 0, 'verification_code' => '', 'is_active' => 'yes', 'created_at' => now(), 'updated_at' => now()]);
    }

    private function attachSessions(Collection $sessionIds, Sibling $sibling, int $parentId): void
    {
        if ($sessionIds->isEmpty()) {
            return;
        }

        $studentIds = DB::table('student_session')->whereIn('id', $sessionIds)->where('brc_id', $sibling->brc_id)->pluck('student_id');
        DB::table('student_session')->whereIn('id', $sessionIds)->where('brc_id', $sibling->brc_id)->update(['student_sibling_id' => $sibling->id]);
        DB::table('students')->whereIn('id', $studentIds)->update(['student_sibling_id' => $sibling->id, 'parent_id' => $parentId]);
    }

    private function authorizeSiblingBranch(Request $request, Sibling $sibling): void
    {
        abort_if($request->integer('brc_id') && $request->integer('brc_id') !== (int) $sibling->brc_id, 403);
    }

    private function students(?int $branchId, ?int $sessionId): Collection
    {
        return $this->studentRows(null, $sessionId, $branchId)->flatten(1);
    }

    private function studentRows(?Collection $siblingIds, ?int $sessionId, ?int $branchId = null): Collection
    {
        if (! Schema::hasTable('student_session')) {
            return collect();
        }

        $rows = DB::table('student_session')->join('students', 'students.id', '=', 'student_session.student_id')->join('classes', 'classes.id', '=', 'student_session.class_id')->join('sections', 'sections.id', '=', 'student_session.section_id')->when($sessionId, fn ($query) => $query->where('student_session.session_id', $sessionId))->when($branchId, fn ($query) => $query->where('student_session.brc_id', $branchId))->when($siblingIds, fn ($query) => $query->whereIn('student_session.student_sibling_id', $siblingIds))->where('students.is_active', 'yes')->orderBy('students.admission_no')->get(['student_session.id as student_session_id', 'student_session.student_sibling_id', 'students.id as student_id', 'students.admission_no', 'students.firstname', 'students.lastname', 'students.father_name', 'classes.class', 'sections.section']);

        return $siblingIds ? $rows->groupBy('student_sibling_id') : $rows;
    }
}
