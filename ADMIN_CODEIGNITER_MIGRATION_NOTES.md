# Admin CodeIgniter Migration Notes

## Route Mapping

| Legacy URL | CodeIgniter controller | CodeIgniter method | CodeIgniter model methods | Laravel controller | Laravel method | Laravel route name | Laravel Blade view | Permission key |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `/admin/admin/dashboard` | `admin/admin` | `dashboard` | Missing CI files | `Admin\DashboardController` | `index` | `admin.dashboard` | `admin.dashboard.index` | `dashboard` |
| `/admin/dashboard` | Optional clean URL | `dashboard` | Missing CI files | `Admin\DashboardController` | `index` | `admin.dashboard.clean` | `admin.dashboard.index` | `dashboard` |
| `/admin/staff` | `admin/staff` | `index` | Missing CI files | `Admin\StaffController` | `index` | `admin.staff.index` | `admin.staff.index` | `staff` |
| `/admin/report` | `admin/report` | `index` | Missing CI files | `Admin\ReportController` | `index` | `admin.report.index` | `admin.reports.index` | `report` |
| `/admin/frontcms` | `admin/frontcms` | `index` | Missing CI files | `Admin\FrontCmsController` | `index` | `admin.frontcms.index` | `admin.frontcms.index` | `front_cms` |
| `/admin/membership` | `admin/membership` | `index` | Missing CI files | `Admin\MembershipController` | `index` | `admin.membership.index` | `admin.membership.index` | `membership` |
| `/admin/qms` | `admin/qms` | `index` | Missing CI files | `Admin\QmsController` | `index` | `admin.qms.index` | `admin.qms.index` | `qms` |
| `/admin/systemnotification` | `admin/adm/enquiry` | `systemnotification` | Missing CI files | `Admin\SystemNotificationController` | `index` | `admin.system-notification.index` | `admin.system-notifications.index` | `system_notification` |

## Database Mapping

| Legacy table | Laravel model | Notes |
| --- | --- | --- |
| `staff` | `App\Models\Staff` | Staff listing, role and branch relationships |
| `roles` | `App\Models\Role` | Admin role checks and superadmin bypass |
| `permission_category` | `App\Models\PermissionCategory` | Legacy RBAC short codes |
| `roles_permissions` | `App\Models\RolePermission` | Legacy RBAC action flags |
| `system_notification` | `App\Models\SystemNotification` | System notification listing, filters, pagination |
| `front_cms_pages` | `App\Models\Page` | Front CMS pages |
| `front_cms_programs` | `App\Models\Post` | Front CMS posts/programs |
| `front_cms_media_gallery` | `App\Models\Gallery` | Front CMS media count |
| `libarary_members` | `App\Models\LibraryMember` | Membership listing; table name is preserved exactly as found |

The original CodeIgniter admin controllers, models, views, JavaScript, AJAX endpoints, and RBAC helper code were not present in this workspace. The Laravel conversion preserves the requested URLs and uses the live database schema available through Laravel Boost. Any additional CI actions such as create, edit, update, delete, send, read, or mark-as-read need the missing CI source files before they can be converted without guessing.
