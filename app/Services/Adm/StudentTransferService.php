<?php

namespace App\Services\Adm;

use App\Models\Adm\StudentSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StudentTransferService
{
    /**
     * @param  array{session_id:int, class_id:int, section_id:int, session_date?:string|null}  $target
     */
    public function transfer(StudentSession $studentSession, array $target): StudentSession
    {
        return DB::transaction(function () use ($studentSession, $target): StudentSession {
            $duplicate = StudentSession::query()
                ->where('student_id', $studentSession->student_id)
                ->where('session_id', $target['session_id'])
                ->where('class_id', $target['class_id'])
                ->where('section_id', $target['section_id'])
                ->whereKeyNot($studentSession->getKey())
                ->exists();

            if ($duplicate) {
                throw ValidationException::withMessages([
                    'student_session' => 'The student is already assigned to the target class, section, and session.',
                ]);
            }

            $studentSession->forceFill([
                'session_id' => $target['session_id'],
                'class_id' => $target['class_id'],
                'section_id' => $target['section_id'],
                'session_date' => $target['session_date'] ?? $studentSession->session_date,
            ])->save();

            return $studentSession->refresh();
        });
    }
}
