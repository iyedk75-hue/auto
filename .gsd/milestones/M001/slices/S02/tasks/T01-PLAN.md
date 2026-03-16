---
estimated_steps: 6
estimated_files: 4
---

# T01: Extend the course schema for bilingual text content

**Slice:** S02 — Bilingual course content management
**Milestone:** M001

## Description

Add real Arabic course text storage and model-level content selection helpers so later admin and candidate work has a stable bilingual data contract.

## Steps

1. Add a migration extending `courses` with Arabic title, description, and content columns.
2. Update `Course` fillable/cast state for the new bilingual fields.
3. Add model helpers or accessors for locale-aware title/description/content selection.
4. Add explicit detection for missing Arabic content.
5. Write focused feature tests proving French mode, Arabic mode, and missing-Arabic behavior.
6. Run the focused course-localization tests and fix failures.

## Must-Haves

- [ ] Courses can store French and Arabic text content separately.
- [ ] Tests prove locale-aware selection behavior and missing-Arabic detection.

## Verification

- `php artisan test --filter=CourseLocalizationTest`
- Confirm the `courses` table migration applies cleanly in the verification copy.

## Inputs

- `.gsd/milestones/M001/M001-ROADMAP.md` — S02 → S03 bilingual content contract
- `app/Models/Course.php` — current monolingual course contract
- `.gsd/milestones/M001/slices/S01/S01-SUMMARY.md` — locale and shell foundation provided by S01

## Expected Output

- `database/migrations/*_add_arabic_content_to_courses_table.php` — new Arabic text columns
- `app/Models/Course.php` — bilingual content helpers and field registration
- `tests/Feature/CourseLocalizationTest.php` — proof for content selection behavior
