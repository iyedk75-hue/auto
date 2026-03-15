---
estimated_steps: 6
estimated_files: 5
---

# T02: Update admin course create/edit flow for bilingual authoring

**Slice:** S02 — Bilingual course content management
**Milestone:** M001

## Description

Expose Arabic lesson text in the admin workflow so the driving school can author and edit bilingual course content without losing existing course media and metadata.

## Steps

1. Update admin course validation rules for bilingual title/description/content fields.
2. Persist the French and Arabic fields in create and update actions.
3. Redesign the course form to present French and Arabic text areas clearly.
4. Ensure existing course records hydrate correctly in edit mode.
5. Add admin feature coverage for bilingual create/update behavior.
6. Run the admin course tests and fix regressions.

## Must-Haves

- [ ] Admin can create and edit Arabic course text fields alongside French ones.
- [ ] Existing course uploads and metadata continue to work unchanged.

## Verification

- `php artisan test --filter=AdminCourseTest`
- Confirm create/edit forms show both French and Arabic text sections.

## Inputs

- `.gsd/milestones/M001/slices/S02/tasks/T01-PLAN.md` — bilingual schema contract
- `app/Http/Controllers/AdminCourseController.php` — current CRUD persistence path
- `resources/views/admin/courses/partials/form.blade.php` — current monolingual course form

## Expected Output

- `app/Http/Controllers/AdminCourseController.php` — bilingual validation and persistence
- `resources/views/admin/courses/partials/form.blade.php` — bilingual authoring UI
- `tests/Feature/AdminCourseTest.php` — admin bilingual CRUD verification
