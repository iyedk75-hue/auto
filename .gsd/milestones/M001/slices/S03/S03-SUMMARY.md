---
id: S03
parent: M001
milestone: M001
provides:
  - Private storage for new lesson media and PDFs
  - Compatibility handling for legacy public lesson assets
  - Authenticated inline media/PDF routes for candidate viewing and admin preview
  - Candidate lesson HTML free of public storage asset URLs
  - Visible viewer deterrence with blocked actions and no-download media controls
requires:
  - slice: S01
    provides: Locale-aware classroom shell and translated viewer chrome
  - slice: S02
    provides: Bilingual lesson content and explicit missing-Arabic behavior
affects: [S05]
key_files:
  - app/Models/Course.php
  - app/Http/Controllers/AdminCourseController.php
  - app/Http/Controllers/CandidateCourseController.php
  - routes/web.php
  - resources/views/candidate/courses/show.blade.php
  - resources/views/admin/courses/partials/form.blade.php
  - resources/js/app.js
  - resources/css/app.css
  - tests/Feature/CourseProtectionTest.php
key_decisions:
  - "Protected lesson files live on private storage; cover images remain public."
  - "Legacy public lesson assets resolve dynamically during the transition instead of being hard-migrated immediately."
  - "Admins may preview protected lesson assets through the same authenticated route family used by candidates."
patterns_established:
  - "Protected lesson assets are consumed only through app-controlled routes."
  - "Viewer deterrence is page-scoped and signaled by explicit page markup and translated feedback copy."
  - "Protection verification combines storage assertions, route assertions, HTML assertions, and frontend build proof."
drill_down_paths:
  - .gsd/milestones/M001/slices/S03/tasks/T01-SUMMARY.md
  - .gsd/milestones/M001/slices/S03/tasks/T02-SUMMARY.md
  - .gsd/milestones/M001/slices/S03/tasks/T03-SUMMARY.md
verification_result: pass
completed_at: 2026-03-15T08:47:00Z
---

# S03: Protected inline learning viewer

**Lesson PDFs and videos now move through protected authenticated routes and the candidate viewer exposes visible deterrence instead of relying on public asset URLs.**

## What Happened

S03 replaced the public-file assumption with a protected lesson-asset contract. New media and PDFs now store on private local storage, while legacy public assets continue to resolve safely through compatibility logic during the transition period.

The candidate lesson page and admin preview links now use authenticated route-based asset delivery. Guests can no longer access lesson assets, candidates can view them inline, and admins can still preview them from course management. This removed direct public storage URLs from the lesson HTML and made access control part of the runtime path.

Finally, the lesson viewer gained visible deterrence behavior: explicit protection messaging, blocked right-click, blocked common save/copy/print/source shortcuts, blocked drag/copy actions, and no-download video controls where the browser supports them. The implementation stays honest about limits — deterrence, not guaranteed DRM — while still raising the barrier for casual copying.

## Deviations

None.

## Files Created/Modified

- `app/Models/Course.php` — protected/legacy lesson asset helpers
- `app/Http/Controllers/AdminCourseController.php` — private lesson asset persistence
- `app/Http/Controllers/CandidateCourseController.php` — authenticated inline asset delivery
- `routes/web.php` — protected media route in addition to protected PDF route
- `resources/views/candidate/courses/show.blade.php` — protected-viewer wiring and deterrence messaging
- `resources/views/admin/courses/partials/form.blade.php` — protected preview links for admins
- `resources/js/app.js` / `resources/css/app.css` — deterrence handlers and feedback UI
- `tests/Feature/CourseProtectionTest.php` — storage, route, HTML, and deterrence verification
