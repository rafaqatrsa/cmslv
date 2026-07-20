<?php

namespace App\Services;

use Illuminate\Http\Request;

class BranchContext
{
    public function __construct(private readonly Request $request) {}

    public function id(): ?int
    {
        $branchId = $this->request->session()->get('brc_id', $this->request->session()->get('branch_id'));

        return is_numeric($branchId) ? (int) $branchId : null;
    }
}
