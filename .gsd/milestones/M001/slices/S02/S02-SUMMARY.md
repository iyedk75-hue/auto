---
id: S02
parent: M001
milestone: M001
provides:
  - Bilingual French/Arabic course text schema on `courses`
  - Locale-aware course text selection helpers on the Course model
  - Bilingual admin course authoring UI and persistence
  - Candidate lesson rendering that uses Arabic content when available
  - Explicit Arabic-unavailable lesson state when Arabic content is missing
requires:
  - slice: S01
    provides: Locale switching, translated shell, and classroom labels
affects: [S03, S05]
key_files:
  - database/migrations/2026_03_15_000014_add_arabic_content_to_courses_table.php
  - app/Models/Course.php
  - app/Http/Controllers/AdminCourseController.php
  - app/Http/Controllers/CandidateCourseController.php
  - resources/views/admin/courses/partials/form.blade.php
  - resources/views/admin/courses/index.blade.php
  - resources/views/candidate/courses/index.blade.php
  - resources/views/candidate/courses/show.blade.php
  - tests/Feature/CourseLocalizationTest.php
  - tests/Feature/AdminCourseTest.php
key_decisions:
  - "French remains the primary required lesson text track; Arabic stays optional so the product can show a truthful unavailable state."
  - "The missing-Arabic state is surfaced explicitly on the lesson page instead of silently showing French content in Arabic mode."
patterns_established:
  - "Bilingual content selection lives in the Course model and controller-prepared view data, not scattered Blade conditionals."
  - "Admin bilingual authoring uses parallel French/Arabic sections in one form."
  - "Feature tests cover both persistence and visible localized lesson behavior."
drill_down_paths:
  - .gsd/milestones/M001/slices/S02/tasks/T01-SUMMARY.md
  - .gsd/milestones/M001/slices/S02/tasks/T02-SUMMARY.md
  - .gsd/milestones/M001/slices/S02/tasks/T03-SUMMARY.md
verification_result: pass
completed_at: 2026-03-15T08:12:00Z
---

# S02: Bilingual course content management

**Courses are now bilingual at the data and UI level: admins can author Arabic lesson text, and candidates see Arabic lesson content or a clear unavailable state.**

## What Happened

S02 extended the course model from a single monolingual text track to a bilingual French/Arabic contract. Arabic title, description, and lesson body now live in dedicated course columns, and the `Course` model exposes locale-aware helper methods that downstream slices can reuse.

The admin course workflow was then upgraded into a bilingual authoring surface. Course create/edit forms now show French and Arabic text sections side by side, controller validation/persistence supports the new fields, and the admin course listing reflects the updated bilingual content model.

On the candidate side, the lesson page now honors Arabic course text when available and shows an explicit “Arabic not available yet” state when Arabic lesson content has not been authored. This closes the user’s requested content-language behavior and gives S03 a stable bilingual lesson surface to build protected delivery on top of.

## Deviations

None.

## Files Created/Modified

- `database/migrations/2026_03_15_000014_add_arabic_content_to_courses_table.php` — Arabic course text columns
- `app/Models/Course.php` — bilingual content helpers
- `app/Http/Controllers/AdminCourseController.php` — bilingual admin persistence
- `app/Http/Controllers/CandidateCourseController.php` — locale-aware candidate lesson preparation
- `resources/views/admin/courses/*.blade.php` — bilingual admin course UI
- `resources/views/candidate/courses/*.blade.php` — locale-aware lesson rendering and missing-Arabic state
- `tests/Feature/CourseLocalizationTest.php` — bilingual lesson behavior verification
- `tests/Feature/AdminCourseTest.php` — bilingual admin CRUD verification
