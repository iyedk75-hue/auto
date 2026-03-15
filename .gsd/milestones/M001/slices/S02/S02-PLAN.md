# S02: Bilingual course content management

**Goal:** Extend the course data model and admin workflow so Arabic course title, description, and lesson body can be authored and rendered as first-class content.
**Demo:** An admin can save Arabic course text for a lesson, and a candidate in Arabic mode sees that Arabic content or a clear “Arabic not available yet” state.

## Must-Haves

- The `courses` data contract stores separate French and Arabic title/description/content values without breaking existing records.
- Admin course create/edit screens expose Arabic text fields alongside the existing French fields.
- Candidate course pages select Arabic text in Arabic mode when available.
- In Arabic mode, missing Arabic course text shows a visible unavailable state instead of silently falling back to French.
- Slice outputs match the S02 → S03 boundary-map contract for bilingual course content selection.

## Proof Level

- This slice proves: integration
- Real runtime required: yes
- Human/UAT required: no

## Verification

- `php artisan test --filter=CourseLocalizationTest`
- `php artisan test --filter=AdminCourseTest`
- `php artisan test --filter=LocaleSwitchTest`
- Production asset build remains green after admin/course form and classroom changes

## Observability / Diagnostics

- Runtime signals: candidate course pages visibly show Arabic text or the explicit unavailable-state message when Arabic content is missing
- Inspection surfaces: admin course form HTML, candidate course detail HTML, focused feature tests for course content selection and persistence
- Failure visibility: missing wiring shows up as wrong persisted course fields, French fallback leakage, or missing unavailable-state text
- Redaction constraints: none

## Integration Closure

- Upstream surfaces consumed: S01 locale middleware, translated shell, course-shell labels, and locale-aware category labels
- New wiring introduced in this slice: bilingual course schema, admin request validation/persistence, locale-aware course text accessors or selection logic
- What remains before the milestone is truly usable end-to-end: protected private lesson delivery, public landing cleanup, and final assembled verification

## Tasks

- [x] **T01: Extend the course schema for bilingual text content** `est:45m`
  - Why: The current course model has only one title/description/content track, so Arabic content has nowhere real to live.
  - Files: `database/migrations/*_add_arabic_content_to_courses_table.php`, `app/Models/Course.php`, `tests/Feature/CourseLocalizationTest.php`
  - Do: Add Arabic text columns plus any compatibility logic needed for existing French-first data, expose locale-aware content helpers on the model, and write tests that prove French/Arabic selection behavior and missing-Arabic detection.
  - Verify: `php artisan test --filter=CourseLocalizationTest`
  - Done when: The model can represent French and Arabic text distinctly and tests prove the selection contract used by the course page.
- [x] **T02: Update admin course create/edit flow for bilingual authoring** `est:1h`
  - Why: The admin must be able to enter Arabic course text before the candidate side can render it.
  - Files: `app/Http/Controllers/AdminCourseController.php`, `resources/views/admin/courses/partials/form.blade.php`, `resources/views/admin/courses/*.blade.php`, `tests/Feature/AdminCourseTest.php`
  - Do: Update validation, persistence, edit hydration, and admin form UI so French and Arabic fields are explicit and translatable, while preserving existing uploads and course metadata.
  - Verify: `php artisan test --filter=AdminCourseTest`
  - Done when: Admin can create and update bilingual course text without regressing existing course CRUD behavior.
- [x] **T03: Render bilingual course text and unavailable state for candidates** `est:45m`
  - Why: The milestone promise is not met until Arabic mode actually changes lesson text on the candidate side.
  - Files: `app/Http/Controllers/CandidateCourseController.php`, `resources/views/candidate/courses/show.blade.php`, `lang/fr/ui.php`, `lang/ar/ui.php`, `tests/Feature/CourseLocalizationTest.php`
  - Do: Use the bilingual course contract on the candidate course page, render Arabic text in Arabic mode, show “Arabic not available yet” when Arabic text is missing, and keep French mode unchanged.
  - Verify: `php artisan test --filter=CourseLocalizationTest && php artisan test --filter=LocaleSwitchTest`
  - Done when: Arabic mode shows Arabic lesson text when present and the explicit unavailable-state copy when absent.

## Files Likely Touched

- `database/migrations/*_add_arabic_content_to_courses_table.php`
- `app/Models/Course.php`
- `app/Http/Controllers/AdminCourseController.php`
- `app/Http/Controllers/CandidateCourseController.php`
- `resources/views/admin/courses/partials/form.blade.php`
- `resources/views/admin/courses/create.blade.php`
- `resources/views/admin/courses/edit.blade.php`
- `resources/views/candidate/courses/show.blade.php`
- `lang/fr/ui.php`
- `lang/ar/ui.php`
- `tests/Feature/CourseLocalizationTest.php`
- `tests/Feature/AdminCourseTest.php`
