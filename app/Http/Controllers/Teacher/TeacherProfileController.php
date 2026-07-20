<?php

namespace App\Http\Controllers\Teacher;

use App\Services\Teacher\TeacherContext;
use Illuminate\View\View;

class TeacherProfileController extends BaseTeacherController
{
    public function show(int $id, TeacherContext $context): View
    {
        $staffId = $context->staffId();

        if ($staffId !== null) {
            abort_unless($staffId === $id, 403);
        }

        return view('teacher.profile.show', [
            'id' => $id,
            'module' => $this->registry->get('profile'),
            'teacher' => $context->teacher(),
        ]);
    }
}
