<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Services\User\UserContext;
use App\Services\User\UserFeeService;
use Illuminate\View\View;

class UserDashboardController extends BaseUserController
{
    public function index(Request $request, UserContext $context, UserFeeService $fees): View
    {
        $student = $context->selectedStudent($request->integer('student_id') ?: null);
        $studentSession = $student ? $context->studentSession($student->id) : null;

        return view('user.dashboard.index', [
            'accessibleStudents' => $context->accessibleStudents(),
            'feeSummary' => $fees->forCurrentStudent($student?->id),
            'student' => $student,
            'studentSession' => $studentSession,
            'user' => $context->user(),
        ]);
    }
}
