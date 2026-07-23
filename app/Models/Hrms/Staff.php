<?php

namespace App\Models\Hrms;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Designation;
use App\Models\RoleBranch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Staff extends HrmsModel
{
    protected $table = 'staff';

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'brc_id');
    }

    public function roleAssignment(): BelongsTo
    {
        return $this->belongsTo(RoleBranch::class, 'role_id');
    }

    public function departmentDetail(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department');
    }

    public function designationDetail(): BelongsTo
    {
        return $this->belongsTo(Designation::class, 'designation');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(StaffAttendance::class, 'staff_id');
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(StaffLeaveRequest::class, 'staff_id');
    }

    public function payslips(): HasMany
    {
        return $this->hasMany(StaffPayslip::class, 'staff_id');
    }

    protected function casts(): array
    {
        return [
            'brc_id' => 'integer',
            'role_id' => 'integer',
            'department' => 'integer',
            'designation' => 'integer',
            'dob' => 'date',
            'date_of_joining' => 'date',
            'date_of_leaving' => 'date',
            'user_id' => 'integer',
            'is_active' => 'integer',
            'disable_at' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
