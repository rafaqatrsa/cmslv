<?php

namespace App\Services\Adm;

use App\Models\Adm\StudentSession;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class StudentTransferService
{
    /** @param array<string, mixed> $data */
    public function transferMany(array $data): int
    {
        return DB::transaction(function () use ($data): int {
            $count = 0;
            $sessions = StudentSession::query()->whereIn('id', $data['check'])->lockForUpdate()->get();

            foreach ($sessions as $studentSession) {
                $this->transferOne($studentSession, $data);
                $count++;
            }

            return $count;
        });
    }

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

    /** @param array<string, mixed> $data */
    private function transferOne(StudentSession $studentSession, array $data): void
    {
        $duplicate = StudentSession::query()
            ->where('student_id', $studentSession->student_id)
            ->where('session_id', (int) $data['session_id'])
            ->where('class_id', (int) $data['class_transfer_id'])
            ->where('section_id', (int) $data['section_transfer_id'])
            ->whereKeyNot($studentSession->getKey())
            ->exists();

        if ($duplicate) {
            throw ValidationException::withMessages(['check' => 'A student already exists in the target class, section, and session.']);
        }

        $oldBranchId = (int) $studentSession->brc_id;
        $targetBranchId = (int) ($data['brc_transfer_id'] ?? $oldBranchId);
        $studentSession->forceFill([
            'brc_id' => $targetBranchId,
            'session_id' => (int) $data['session_id'],
            'class_id' => (int) $data['class_transfer_id'],
            'section_id' => (int) $data['section_transfer_id'],
        ])->save();

        if (($data['fee_transfer_mode'] ?? 'old_fees') === 'old_fees') {
            $this->moveExistingFees($studentSession->getKey(), $oldBranchId, $targetBranchId);

            return;
        }

        $this->replaceFees($studentSession, $oldBranchId, $targetBranchId, (int) $data['class_transfer_id']);
    }

    private function moveExistingFees(int $studentSessionId, int $oldBranchId, int $targetBranchId): void
    {
        if (! Schema::hasTable('student_fees_assign')) {
            return;
        }

        DB::table('student_fees_assign')
            ->where('student_session_id', $studentSessionId)
            ->where('brc_id', $oldBranchId)
            ->update(['brc_id' => $targetBranchId]);
    }

    private function replaceFees(StudentSession $studentSession, int $oldBranchId, int $targetBranchId, int $classId): void
    {
        if (! Schema::hasTable('student_fees_assign') || ! Schema::hasTable('fee_groups_feetype')) {
            return;
        }

        $oldFees = DB::table('student_fees_assign')
            ->where('student_session_id', $studentSession->getKey())
            ->where('brc_id', $oldBranchId)
            ->get();
        $discounts = $oldFees->keyBy(fn ($fee): string => $fee->feetype_id.'_'.$fee->frequency);

        DB::table('student_fees_assign')->where('student_session_id', $studentSession->getKey())->delete();
        $feeColumns = Schema::getColumnListing('student_fees_assign');
        $feeRows = DB::table('fee_groups_feetype')
            ->where('brc_id', $targetBranchId)
            ->where('fee_class_id', $classId)
            ->whereIn('frequency', ['Monthly', 'Yearly'])
            ->orderBy('feetype_id')
            ->get();

        foreach ($feeRows as $fee) {
            $discount = (float) ($discounts->get($fee->feetype_id.'_'.$fee->frequency)?->discount_amount ?? 0);
            $amount = (float) $fee->amount;
            $payload = [
                'brc_id' => $targetBranchId,
                'student_id' => $studentSession->student_id,
                'student_session_id' => $studentSession->getKey(),
                'feetype_id' => $fee->feetype_id,
                'frequency' => $fee->frequency,
                'fee_amount' => $amount,
                'discount_amount' => $discount,
                'current_amount' => max(0, $amount - $discount),
                'created_by' => auth()->id(),
                'is_active' => 'yes',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            DB::table('student_fees_assign')->insert(Arr::only($payload, $feeColumns));
        }
    }
}
