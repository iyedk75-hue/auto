---
id: T02
parent: S02
milestone: M001
provides:
  - Admin validation and persistence for French and Arabic course text fields
  - Bilingual admin course create/edit form UI with French and Arabic sections
  - Localized admin course index/create/edit labels in French and Arabic
  - Feature coverage for bilingual course create/update behavior
requires:
  - slice: S02
    provides: Bilingual course schema and model helpers from T01
  - slice: S01
    provides: Locale switching and translated admin shell
affects: [S03, S05]
key_files:
  - app/Http/Controllers/AdminCourseController.php
  - resources/views/admin/courses/create.blade.php
  - resources/views/admin/courses/edit.blade.php
  - resources/views/admin/courses/index.blade.php
  - resources/views/admin/courses/partials/form.blade.php
  - lang/fr/ui.php
  - lang/ar/ui.php
  - tests/Feature/AdminCourseTest.php
key_decisions:
  - "French remains the required primary text track; Arabic fields are optional so the missing-Arabic state can still be expressed honestly."
  - "Admin course screens were localized now instead of later because bilingual authoring is itself part of the admin-facing experience."
patterns_established:
  - "Bilingual authoring uses parallel French and Arabic sections in the same form rather than a language toggle hiding one set of fields."
  - "Admin CRUD tests assert both form rendering and bilingual persistence."
drill_down_paths:
  - .gsd/milestones/M001/slices/S02/tasks/T02-PLAN.md
duration: 50m
verification_result: pass
completed_at: 2026-03-15T08:00:00Z
---

# T02: Update admin course create/edit flow for bilingual authoring

**The admin course workflow can now create and update French and Arabic lesson text side by side, with tests proving the bilingual fields persist correctly.**

## What Happened

Updated `AdminCourseController` so create and update actions validate and persist `title_ar`, `description_ar`, and `content_ar` alongside the existing French fields. Flash messages for course create/update/delete now run through the translation layer instead of hard-coded French strings.

The admin course views were upgraded into a bilingual authoring surface: the form now has separate French and Arabic content sections, Arabic textareas use RTL direction, and the index/create/edit screens now use translated labels from the admin course translation block. The index also surfaces the Arabic title when available, which gives admins a quick visual confirmation that bilingual content exists.

Verification used a new `AdminCourseTest` suite that proves the Arabic create form renders, bilingual text fields can be created, and bilingual fields can be updated without regressing the rest of the course record.

## Deviations

None.

## Files Created/Modified

- `app/Http/Controllers/AdminCourseController.php` — bilingual validation, persistence, and translated flash messages
- `resources/views/admin/courses/create.blade.php` — localized create page
- `resources/views/admin/courses/edit.blade.php` — localized edit page
- `resources/views/admin/courses/index.blade.php` — localized index page and Arabic title visibility
- `resources/views/admin/courses/partials/form.blade.php` — bilingual French/Arabic authoring UI
- `lang/fr/ui.php` / `lang/ar/ui.php` — admin course translation strings
- `tests/Feature/AdminCourseTest.php` — bilingual admin CRUD verification
