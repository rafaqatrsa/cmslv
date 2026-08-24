<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\User\UserContext;
use App\Services\User\UserFeeService;
use Illuminate\View\View;

class UserFeeController extends Controller
{
    public function index(Request $request, UserContext $context, UserFeeService $fees): View
    {
        $studentId = $request->integer('student_id') ?: null;

        if ($studentId !== null) {
            abort_unless($context->canAccessStudent($studentId), 403);
        }

        return view('user.fees.index', [
            'accessibleStudents' => $context->accessibleStudents(),
            'feeSummary' => $fees->forCurrentStudent($studentId),
            'student' => $context->selectedStudent($studentId),
        ]);
    }
}
