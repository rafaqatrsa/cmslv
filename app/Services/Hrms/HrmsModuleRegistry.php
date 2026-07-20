<?php

namespace App\Services\Hrms;

use App\Models\Hrms\HrDocument;
use App\Models\Hrms\HrManual;
use App\Models\Hrms\Staff;

class HrmsModuleRegistry
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public function all(): array
    {
        return [
            'dashboard' => $this->module('HRMS Dashboard', Staff::class, 'staff', 'admin.hrms.dashboard', 'hrm', ['employee_id', 'name', 'surname', 'email'], ['employee_id', 'name', 'surname', 'role_id', 'is_active']),
            'documents' => $this->module('HR Documents', HrDocument::class, 'staff', 'admin.hrms.documents.index', 'documentshrm', ['employee_id', 'name', 'resume', 'joining_letter', 'other_document_file'], ['employee_id', 'name', 'resume', 'joining_letter', 'other_document_file']),
            'manual' => $this->module('HR Manual', HrManual::class, 'manual_supporthrm', 'admin.hrms.manual.index', 'manualhrm', ['doc_title', 'doc_type', 'note'], ['doc_title', 'doc_type', 'doc', 'video_link', 'is_active']),
            'staff' => $this->module('Staff', Staff::class, 'staff', 'admin.hrms.staff.index', 'staff', ['employee_id', 'name', 'surname', 'email', 'contact_no'], ['employee_id', 'name', 'surname', 'department', 'designation', 'is_active']),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function get(string $key): array
    {
        return $this->all()[$key];
    }

    /**
     * @param  class-string  $model
     * @param  array<int, string>  $search
     * @param  array<int, string>  $columns
     * @return array<string, mixed>
     */
    private function module(string $label, string $model, string $table, string $route, string $permission, array $search, array $columns): array
    {
        return compact('label', 'model', 'table', 'route', 'permission', 'search', 'columns');
    }
}
