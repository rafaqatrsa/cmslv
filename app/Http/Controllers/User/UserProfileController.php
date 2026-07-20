<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\User\UserContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class UserProfileController extends Controller
{
    public function show(Request $request, UserContext $context): View
    {
        $student = $context->selectedStudent($request->integer('student_id') ?: null);

        abort_unless($student !== null || $context->isParent() || ! Schema::hasTable('students'), 404);

        return view('user.profile.show', [
            'accessibleStudents' => $context->accessibleStudents(),
            'student' => $student,
            'studentSession' => $student ? $context->studentSession($student->id) : null,
            'user' => $context->user(),
        ]);
    }
}
