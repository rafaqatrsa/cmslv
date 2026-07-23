# CodeIgniter To Laravel Migration

## Project Overview

- Legacy source of truth: `C:\xampp\htdocs\tntlaravel\cmsc`
- Laravel target: `C:\xampp\htdocs\tntlaravel\cmslv`
- Database strategy: reuse the existing `tntlaravel` database and adapt Laravel to the legacy schema
- Current phase: `Phase 0: Project Foundation and Compatibility`
- Current status date: `2026-07-23`

## Module Inventory

### Core Admin

- Dashboard
- Reports
- Front CMS
- Membership
- QMS
- System notifications

### Academics

- Books
- Chapters
- Conference
- Day setting
- Documents
- Domains and domain modules
- Exam groups, results, schedules
- Google Meet
- Grooming
- Homework
- Lessons
- Members
- Online exams
- Paper generation
- Questions
- Subjects and subject groups
- Syllabus
- Teacher
- Term settings
- Test groups, results, schedules
- Timetable
- Topics
- Week settings

### Accounts / Finance

- Accounts
- Brands
- Class book sets
- Contra vouchers
- Documents
- Expenses
- Fee master
- Invoice book sets and returns
- Item categories
- Journal vouchers
- Payments
- Payroll
- Products and product types
- Purchases and purchase returns
- Receipts
- Royalty
- Sales and sales returns
- Stock
- Student fee
- Suppliers
- Units

### Administration

- Achievement
- Leave approval
- Attendance
- Calendar
- Chat
- Complaints
- Content and content types
- Dispatch
- Documents
- Enquiry
- General call and general remarks
- ID card generators
- Leave requests
- Mail and SMS
- Notifications
- Receive
- Reference
- Siblings
- Source
- Staff attendance and staff ID cards
- Student transfer
- Student attendance
- Student
- Student registration
- Student ID cards
- Subject attendance
- Video tutorials
- Visitor purposes

### HRMS

- Dashboard
- Manual and documents
- Staff directory
- Disabled staff directory
- Staff profile
- Staff create and edit
- Attendance AJAX
- Permissions
- Password change
- Import and export
- Ratings
- Branch staff lookup

### Front / Public CMS

- Banners
- Events
- Gallery
- Media
- Menus
- Notices
- Pages

### Settings / Master Data

- Academic years
- Areas and locations
- Banks
- Branch settings
- Categories
- Classes and sections
- Departments and designations
- Disable reasons
- Education
- Institutes
- Leave types
- Modules
- Notification settings
- Occupations
- Organizations
- Roles
- Sessions
- Skills
- SMS, email, WhatsApp config
- System settings
- Training
- University boards

### Teacher Portal

- Dashboard and profile
- Password change
- Leave approval
- Conference
- Exam result and exam schedule
- Google Meet
- Grooming
- Homework
- Lesson
- Student attendance
- Syllabus
- Term settings
- Test result and schedule
- Timetable

### User Portal

- Dashboard and profile
- Password and username change
- Attendance
- Leave apply
- Book
- Conference
- Content
- Google Meet
- Video tutorials
- Fees

### Cross-Cutting

- Authentication and session flow
- Roles, permissions, RBAC
- Biometric routes
- Cron routes
- Uploads and downloads
- Printing and reports
- AJAX conventions
- Legacy helpers and libraries

## Laravel Conversion Status

### Reusable Progress Found

- Legacy route namespaces largely mirrored in Laravel
- Many legacy table-backed models already exist
- Shared module registries and index services exist for admin, academics, accounts, ADM, HRMS, teacher, user, and front
- Legacy login URL compatibility exists for staff and site users
- Teacher and user portal shells are in place
- Cron and biometric shells are in place

### Incomplete Or Placeholder Areas

- Many admin controllers still resolve through generic `Base*Controller` + `modules.index` pages
- Feature parity is missing for most CRUD workflows
- AJAX handlers, reports, print views, import/export, approval flows, and exact legacy validation behavior are still largely absent
- HRMS has the deepest partial conversion but is still incomplete

## Shared Dependencies

- Legacy database schema and non-standard table naming
- Session keys: branch, academic session, financial year
- Shared layouts, assets, and sidebar state
- RBAC permission categories and role permissions
- Legacy uploads and download directory structure
- Legacy active-state values such as `1`, `'1'`, and `'yes'`

## Phase Roadmap

### Phase 0: Project Foundation and Compatibility

- Objective: align Laravel runtime assumptions with the legacy application before module-by-module conversion
- Scope:
  - environment configuration
  - database connection verification
  - auth and session compatibility
  - branch / academic session / financial year context
  - shared compatibility config
  - migration tracking document
  - baseline tests for shared compatibility behavior
- Dependencies: none
- Complexity: medium
- Risks:
  - Laravel was previously failing to connect to MySQL at `127.0.0.1:3306`
  - starter-kit defaults still leak into the current Laravel configuration
- Completion criteria:
  - Laravel is configured around the legacy runtime assumptions
  - shared session/context handling is normalized
  - auth compatibility for legacy staff login remains green
  - migration document exists and is updated

### Phase 1: Identity, Access, And Core Settings

- 1.1 Authentication and session parity
- 1.2 Roles, permissions, menu visibility
- 1.3 Branch, session, and financial-year context
- 1.4 Settings and master data

### Phase 2: Shared UI And Legacy Compatibility Layer

- 2.1 Shared admin layout, header, sidebar, footer
- 2.2 Shared forms, tables, modals, and list patterns
- 2.3 Upload, download, and print helpers
- 2.4 AJAX response conventions

### Phase 3: HRMS Foundation

- 3.1 HRMS dashboard, manual, documents shell
- 3.2 Departments, designations, HR master data
- 3.3 Staff directory, disabled directory, profile

### Phase 4: HRMS Operations

- 4.1 Staff create, edit, documents, password, permissions
- 4.2 Attendance
- 4.3 Leave
- 4.4 Payroll
- 4.5 Recruitment, rating, reports

### Phase 5: ADM Student And Front Desk

- 5.1 Enquiry, content, reference, source, visitor modules
- 5.2 Student registration and student core
- 5.3 Siblings, transfers, ID cards
- 5.4 Student and staff attendance, leave approval

### Phase 6: Academics

- 6.1 Subjects, groups, timetable, teacher assignment
- 6.2 Lessons, syllabus, homework
- 6.3 Exams, tests, results, schedules
- 6.4 Conference, Google Meet, grooming, online exams, paper generation

### Phase 7: Accounts And Finance

- 7.1 Accounts, vouchers, ledgers
- 7.2 Fee master and student fee
- 7.3 Purchases, sales, stock
- 7.4 Payroll finance integration, royalty, reports

### Phase 8: Front CMS And Public Site

- 8.1 Pages, menus, media
- 8.2 Banners, events, gallery, notices
- 8.3 Registration, contact, public flows

### Phase 9: Teacher And User Portals

- 9.1 Teacher parity
- 9.2 Student and parent parity
- 9.3 Portal-specific permissions and dashboards

### Phase 10: System Integrations And Final Hardening

- 10.1 Cron, biometric, APIs
- 10.2 Imports, exports, printing, reports
- 10.3 Regression testing, UAT, cleanup

## Phase Ordering Rationale

- Identity and settings come before protected modules because all modules depend on them
- Shared UI comes before deep module conversion so visible parity is consistent
- HRMS comes early because it already has the deepest Laravel foothold
- ADM, academics, and accounts follow after core compatibility and shared contexts are stable
- Portals and system integrations come later because they depend on the converted admin-side domains

## Risks And Blockers

- Laravel environment and legacy runtime assumptions are not yet fully aligned
- Existing Laravel worktree already has partial HRMS conversion in progress
- Some modules appear present only as route/controller scaffolding
- Legacy helpers and autoloaded models are heavily shared across modules
- Mixed active-state and session-key conventions can create subtle mismatches

## Special Attention Areas

- `application/config/*`
- autoloaded helpers, libraries, and models
- role and permission tables
- session aliases and context selection
- upload and download paths
- print/report views
- raw SQL and cross-table joins

## Phase Status

| Phase | Status | Notes |
| --- | --- | --- |
| Phase 0 | Completed | Shared compatibility layer verified on 2026-07-23 by running tests through `C:\xampp\php\php.exe` because the system `php` binary points to a separate PHP 8.5 installation |
| Phase 1 | Not Started | Waiting on Phase 0 completion |
| Phase 2 | Not Started | Waiting on Phase 1 |
| Phase 3 | Not Started | Waiting on Phase 2 baseline |
| Phase 4 | Not Started | Waiting on Phase 3 |
| Phase 5 | Not Started | Waiting on Phases 1-2 |
| Phase 6 | Not Started | Waiting on Phases 1-2 and student/core data |
| Phase 7 | Not Started | Waiting on Phases 1-2 and shared finance contexts |
| Phase 8 | Not Started | Waiting on Phase 2 |
| Phase 9 | Not Started | Waiting on admin-side domain conversion |
| Phase 10 | Not Started | Final integration phase |

## Phase 0 Checklist

| Item | Status | Notes |
| --- | --- | --- |
| Review legacy environment and DB config | Completed | Legacy CodeIgniter uses `localhost`, database `tntlaravel`, file sessions |
| Review Laravel environment and auth/session defaults | Completed | Starter defaults still existed in `.env.example`; local `.env` used `127.0.0.1` |
| Create shared legacy compatibility config | Completed | Introduced `config/legacy.php` |
| Normalize session alias handling | Completed | Shared contexts and middleware now use config-driven alias lists |
| Normalize active-state handling for core auth models | Completed | Shared auth and core models now accept legacy active values |
| Align local Laravel DB host with legacy runtime | Completed | Local `.env` updated from `127.0.0.1` to `localhost` |
| Update environment template for legacy defaults | Completed | `.env.example` updated to MySQL + staff auth defaults |
| Add compatibility tests | Completed | Added unit coverage for shared legacy config and context aliases |
| Run focused tests and formatter | Completed | `C:\xampp\php\php.exe artisan test --compact tests/Feature/LegacyFoundationCompatibilityTest.php tests/Feature/CodeIgniterAuthRoutesTest.php tests/Unit/LegacyCompatibilityConfigTest.php` and `C:\xampp\php\php.exe vendor\bin\pint --dirty --format agent` passed on 2026-07-23 |

## Definition Of Done

### Phase Done

- approved scope converted
- behavior matches legacy for the shared foundation
- no placeholder logic in the shared compatibility layer
- tests executed for touched behavior
- document updated

### Project Done

- every legacy module available in Laravel
- same database used safely
- UI and workflows match CodeIgniter
- permissions, reports, AJAX, imports/exports, uploads/downloads all work
- no placeholder modules remain
- final regression completed
