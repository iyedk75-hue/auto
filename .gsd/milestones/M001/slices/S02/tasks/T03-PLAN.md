---
estimated_steps: 5
estimated_files: 5
---

# T03: Render bilingual course text and unavailable state for candidates

**Slice:** S02 — Bilingual course content management
**Milestone:** M001

## Description

Apply the bilingual course data contract to the candidate lesson page so Arabic mode shows Arabic text when available and the explicit unavailable-state message when it is not.

## Steps

1. Update the candidate course controller or view data preparation to use locale-aware course text selection.
2. Render localized title/description/content from the bilingual fields.
3. Add the explicit “Arabic not available yet” state for Arabic mode with missing Arabic text.
4. Keep French mode unchanged and avoid silent fallback in Arabic mode.
5. Run the course localization tests and locale regression checks.

## Must-Haves

- [ ] Candidate course pages show Arabic lesson text in Arabic mode when it exists.
- [ ] Candidate course pages show the explicit unavailable-state message when Arabic text is missing.

## Verification

- `php artisan test --filter=CourseLocalizationTest && php artisan test --filter=LocaleSwitchTest`
- Confirm the unavailable-state copy appears only in Arabic mode when Arabic lesson text is absent.

## Inputs

- `.gsd/milestones/M001/slices/S02/tasks/T01-PLAN.md` — locale-aware content selection contract
- `.gsd/milestones/M001/slices/S01/S01-SUMMARY.md` — translated classroom shell and locale behavior
- `resources/views/candidate/courses/show.blade.php` — current classroom detail surface

## Expected Output

- `app/Http/Controllers/CandidateCourseController.php` or `app/Models/Course.php` — locale-aware course content resolution
- `resources/views/candidate/courses/show.blade.php` — rendered Arabic content and unavailable-state UI
- `tests/Feature/CourseLocalizationTest.php` — candidate-side bilingual content verification
