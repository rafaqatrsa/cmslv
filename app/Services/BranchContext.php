<?php

namespace App\Services;

class BranchContext
{
    public function id(): ?int
    {
        foreach (config('legacy.session.branch_keys', ['brc_id', 'branch_id']) as $key) {
            $branchId = session($key);

            if (is_numeric($branchId)) {
                return (int) $branchId;
            }
        }

        return null;
    }
}
