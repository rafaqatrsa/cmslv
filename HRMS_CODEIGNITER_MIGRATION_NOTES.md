# HRMS CodeIgniter Migration Notes

The Laravel workspace does not contain the original CodeIgniter HRMS controllers, models, views, helpers, libraries, JavaScript, AJAX endpoints, DataTables configuration, reports, or upload handlers. A workspace search for `/admin/hrms`, `documentshrm`, `manualhrm`, and HRMS controller names returned no CodeIgniter source files.

This implementation preserves the known `/admin/hrms/...` legacy URLs and creates a Laravel 12, table-backed conversion foundation. Staff, HR manual, attendance, leave, and payroll integration models use actual database tables. Source-dependent workflows such as staff creation, transfers, resignation, document replacement, AJAX responses, and payroll formulas are intentionally not invented.

## Migration Map

| Legacy URL | CodeIgniter controller | CodeIgniter method | CodeIgniter model method | Database tables | Laravel controller | Laravel method | Laravel route name | Laravel model or service | Blade view | Permission key | External module dependency |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `/admin/hrms/hrm` | Missing source | `index` | Missing source | `staff`, `staff_attendance`, `staff_leave_request`, `staff_payslip` | `HrmsDashboardController` | `index` | `admin.hrms.dashboard` | `Staff`, `HrmsIndexService` | `admin.hrms.modules.index` | `hrm` | Dashboard metrics, attendance/payroll formulas missing |
| `/admin/hrms/documentshrm` | Missing source | `index` | Missing source | `staff` document columns: `resume`, `joining_letter`, `other_document_file` | `HrDocumentController` | `index` | `admin.hrms.documents.index` | `HrDocument`, `HrDocumentService` | `admin.hrms.modules.index` | `documentshrm` | Upload directories and replacement rules missing |
| `/admin/hrms/manualhrm` | Missing source | `index` | Missing source | `manual_supporthrm` | `HrManualController` | `index` | `admin.hrms.manual.index` | `HrManual` | `admin.hrms.modules.index` | `manualhrm` | Rich-text/publish workflow missing |
| `/admin/hrms/staff` | Missing source | `index` | Missing source | `staff`, `staff_academic`, `staff_certifications`, `staff_experiences`, `staff_pay`, `staff_payslip` | `StaffController` | `index` | `admin.hrms.staff.index` | `Staff`, `StaffStatusService` | `admin.hrms.modules.index` | `staff` | Login, transfer, promotion, resignation, payroll workflows missing |

## Implemented Laravel Files

- Routes: `routes/web.php`
- Controllers: `app/Http/Controllers/Admin/Hrms/*Controller.php`
- Models: `app/Models/Hrms/*.php`
- Services: `app/Services/Hrms/HrmsIndexService.php`, `HrmsModuleRegistry.php`, `HrDocumentService.php`, `StaffStatusService.php`
- Views: `resources/views/admin/hrms/partials/nav.blade.php`, `resources/views/admin/hrms/modules/index.blade.php`
- Tests: `tests/Feature/HrmsLegacyRoutesTest.php`, `tests/Unit/HrmsModuleRegistryTest.php`

## Unresolved Dependencies

- Original CodeIgniter HRMS controllers, model methods, views, and JavaScript
- Full legacy method routes beyond the top-level URLs
- DataTables/AJAX payload and response keys
- Staff creation/update validation and login-account linking rules
- Staff transfer, promotion, resignation, termination, and history rules
- Payroll formulas and salary-slip posting rules
- Attendance and leave-balance formulas
- HR document upload directories, file constraints, and replacement policy
- HR manual publishing/status workflow and revision history
- Exact permission keys for add/edit/delete/status actions

## Artisan Commands Used Or Relevant

```bash
php artisan route:list --path=admin/hrms
vendor/bin/pint --dirty --format agent
php artisan test --compact tests/Feature/HrmsLegacyRoutesTest.php tests/Unit/HrmsModuleRegistryTest.php
```
