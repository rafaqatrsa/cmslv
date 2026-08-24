<?php

namespace App\Services\Adm;

use App\Models\Adm\Student;
use App\Models\Adm\StudentSession;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class StudentPromotionService
{
    /** @param array<string, mixed> $data */
    public function promote(Collection $students, array $data): int
    {
        return DB::transaction(function () use ($students, $data): int {
            $promoted = 0;

            foreach ($students as $student) {
                if (! $student instanceof Student) {
                    continue;
                }

                $student->loadMissing(['sessions']);
                $source = $student->sessions->first(fn (StudentSession $session): bool =>
                    (int) $session->session_id === (int) ($data['source_session_id'] ?? 0)
                    && (int) $session->brc_id === (int) $data['brc_id_post']);

                if (! $source) {
                    continue;
                }

                $result = data_get($data, "result.{$student->id}", 'pass');
                $nextWorking = data_get($data, "next_working.{$student->id}", 'countinue');

                if ($nextWorking === 'leave') {
                    $source->forceFill(['is_alumni' => 2])->save();
                    $student->forceFill(['is_active' => 'no'])->save();
                    continue;
                }

                if ($result === 'fail') {
                    $targetClass = (int) $data['class_post'];
                    $targetSection = $data['section_post'] ?? null;
                } else {
                    $targetClass = (int) $data['class_promote_id'];
                    $targetSection = $data['section_promote_id'] ?? null;
                }

                $duplicate = StudentSession::query()
                    ->where('student_id', $student->id)
                    ->where('brc_id', (int) $data['brc_id_post'])
                    ->where('session_id', (int) $data['session_id'])
                    ->where('class_id', $targetClass)
                    ->when($targetSection === null, fn ($query) => $query->whereNull('section_id'), fn ($query) => $query->where('section_id', (int) $targetSection))
                    ->exists();

                if ($duplicate) {
                    continue;
                }

                $newSession = StudentSession::query()->create([
                    'brc_id' => (int) $data['brc_id_post'],
                    'student_id' => $student->id,
                    'class_id' => $targetClass,
                    'section_id' => $targetSection,
                    'session_id' => (int) $data['session_id'],
                    'student_sibling_id' => $source->student_sibling_id,
                    'session_date' => now()->toDateString(),
                ]);

                $this->assignFees($source, $newSession, $targetClass, $data);
                $promoted++;
            }

            return $promoted;
        });
    }

    /** @param array<string, mixed> $data */
    private function assignFees(StudentSession $source, StudentSession $target, int $classId, array $data): void
    {
        if (! Schema::hasTable('student_fees_assign') || ! Schema::hasTable('fee_groups_feetype')) {
            return;
        }

        $mode = $data['fee_promotion_mode'] ?? 'previous_discount';
        $oldFees = DB::table('student_fees_assign')
            ->where('brc_id', $source->brc_id)
            ->where('student_session_id', $source->id)
            ->get()
            ->keyBy('feetype_id');
        $columns = Schema::getColumnListing('student_fees_assign');
        $feeRows = DB::table('fee_groups_feetype')
            ->where('brc_id', $target->brc_id)
            ->where('fee_class_id', $classId)
            ->whereIn('frequency', ['Monthly', 'Yearly'])
            ->orderBy('feetype_id')
            ->get();

        foreach ($feeRows as $fee) {
            $old = $oldFees->get($fee->feetype_id);
            $discount = (float) ($old->discount_amount ?? 0);
            $current = (float) ($old->current_amount ?? 0);

            if ($mode === 'full_fees') {
                $current = (float) $fee->amount;
            } elseif ($mode === 'increment_previous_tuition_fee_amount') {
                $current += (float) $data['promotion_increment_amount'];
            } elseif ($mode === 'increment_previous_tuition_fee_percentage') {
                $current += $current * (float) $data['promotion_increment_percentage'] / 100;
            }

            $payload = [
                'brc_id' => $target->brc_id,
                'student_id' => $target->student_id,
                'student_session_id' => $target->id,
                'feetype_id' => $fee->feetype_id,
                'frequency' => $fee->frequency,
                'fee_amount' => $fee->amount,
                'discount_amount' => (float) $fee->amount - $current,
                'current_amount' => $current,
                'created_by' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            DB::table('student_fees_assign')->insert(Arr::only($payload, $columns));
        }
    }
}
