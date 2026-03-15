# S02: Admin multi-resource management

**Goal:** Give admins a real workflow to add, edit, order, and remove many course resources of type video, PDF, and note.
**Demo:** An admin opens a course, manages a list of ordered resources, and can create/edit/delete note or file resources for that course.

## Must-Haves

- Admin can create resources of type `video`, `pdf`, and `note` for a course.
- Admin can edit resource metadata and content.
- Admin can set manual order explicitly.
- Admin can remove a resource and its protected file when applicable.
- The admin UI uses the child-resource model rather than writing back into legacy `media_path` / `pdf_path` fields.

## Proof Level

- This slice proves: integration
- Real runtime required: yes
- Human/UAT required: no

## Verification

- `php artisan test --filter=AdminCourseResourceTest`
- `php artisan test --filter=CourseTransitionTest`
- `npm run build`

## Observability / Diagnostics

- Runtime signals: admin pages visibly show ordered resource lists and resource counts per course
- Inspection surfaces: nested admin resource routes, protected file existence assertions in tests, rendered admin list/forms
- Failure visibility: broken CRUD shows up as missing resource rows, order mismatches, or orphaned protected files in tests
- Redaction constraints: note content is normal course content; protected file paths remain internal

## Integration Closure

- Upstream surfaces consumed: M002/S01 resource schema and normalized contract, M001 protected asset storage pattern
- New wiring introduced in this slice: nested admin course-resource routes, controller validation, per-type create/edit forms, resource deletion cleanup
- What remains before the milestone is truly usable end-to-end: student classroom-style list UI and final integrated proof with protected per-resource viewing

## Tasks

- [x] **T01: Add nested admin resource CRUD routes and controller logic** `est:1h`
  - Why: The admin needs a real backend workflow for resource management before UI polish matters.
  - Files: `routes/web.php`, `app/Http/Controllers/AdminCourseResourceController.php`, `tests/Feature/AdminCourseResourceTest.php`
  - Do: Add nested resource routes under courses, validate note/file resource creation and updates, preserve protected file storage for file resources, and delete protected files on resource removal.
  - Verify: `php artisan test --filter=AdminCourseResourceTest`
  - Done when: Resource create/update/delete/order flows work through real nested endpoints and tests prove them.
- [x] **T02: Build admin resource management pages and forms** `est:1h`
  - Why: Admins need a usable interface for managing multiple supports, not just backend endpoints.
  - Files: `resources/views/admin/course-resources/*.blade.php`, `resources/views/admin/courses/index.blade.php`, `resources/views/admin/courses/edit.blade.php`, `lang/fr/ui.php`, `lang/ar/ui.php`
  - Do: Add resource index/create/edit pages, per-type form behavior for note versus file resources, and clear manual ordering inputs, then link the workflow from course management.
  - Verify: `php artisan test --filter=AdminCourseResourceTest && npm run build`
  - Done when: An admin can navigate from a course to its resource manager and use the forms to manage ordered supports.
- [x] **T03: Surface resource counts and transition-safe admin behavior** `est:45m`
  - Why: The admin side should make it obvious which courses now use the new multi-resource path and should not regress older courses during the transition.
  - Files: `app/Http/Controllers/AdminCourseController.php`, `resources/views/admin/courses/index.blade.php`, `tests/Feature/AdminCourseResourceTest.php`
  - Do: Show resource counts and management links in the course list, preserve visibility of legacy single-resource state where useful, and verify the admin flow behaves correctly whether a course has child resources or only legacy fields.
  - Verify: `php artisan test --filter=AdminCourseResourceTest && php artisan test --filter=CourseTransitionTest`
  - Done when: Admin course management clearly exposes the new resource workflow and still behaves safely for older courses.

## Files Likely Touched

- `routes/web.php`
- `app/Http/Controllers/AdminCourseController.php`
- `app/Http/Controllers/AdminCourseResourceController.php`
- `resources/views/admin/courses/index.blade.php`
- `resources/views/admin/courses/edit.blade.php`
- `resources/views/admin/course-resources/index.blade.php`
- `resources/views/admin/course-resources/create.blade.php`
- `resources/views/admin/course-resources/edit.blade.php`
- `resources/views/admin/course-resources/partials/form.blade.php`
- `lang/fr/ui.php`
- `lang/ar/ui.php`
- `tests/Feature/AdminCourseResourceTest.php`
