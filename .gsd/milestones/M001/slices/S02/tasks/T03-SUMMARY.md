---
id: T03
parent: S02
milestone: M001
provides:
  - Locale-aware candidate course rendering using bilingual course helpers
  - Explicit unavailable-state UI for Arabic mode when Arabic lesson text is missing
  - Arabic-aware course list card rendering with localized text when available
  - Feature coverage for candidate-side Arabic course rendering and missing-Arabic behavior
requires:
  - slice: S02
    provides: Bilingual course schema and admin authoring from T01 and T02
  - slice: S01
    provides: Locale switching and translated classroom shell
affects: [S03, S05]
key_files:
  - app/Http/Controllers/CandidateCourseController.php
  - resources/views/candidate/courses/index.blade.php
  - resources/views/candidate/courses/show.blade.php
  - lang/fr/ui.php
  - lang/ar/ui.php
  - tests/Feature/CourseLocalizationTest.php
key_decisions:
  - "Arabic-unavailable handling is explicit on the course detail page; French lesson text is not silently shown as Arabic when no Arabic translation exists."
  - "Course cards may still fall back field-by-field for discoverability, but the lesson page is the authoritative place where missing Arabic is surfaced clearly."
patterns_established:
  - "Candidate lesson views consume prepared localized values plus an explicit unavailable-state flag instead of embedding all locale logic inline."
  - "Behavioral tests for bilingual content assert the visible unavailable state, not just model helpers."
drill_down_paths:
  - .gsd/milestones/M001/slices/S02/tasks/T03-PLAN.md
duration: 40m
verification_result: pass
completed_at: 2026-03-15T08:10:00Z
---

# T03: Render bilingual course text and unavailable state for candidates

**The candidate lesson page now respects Arabic course text and explicitly tells the student when Arabic lesson content does not exist yet.**

## What Happened

Updated `CandidateCourseController` to prepare locale-aware course title, description, and content values for the candidate surfaces. In Arabic mode, the detail page now uses Arabic text when present and sets a dedicated `showArabicUnavailable` state when the course has no Arabic translation at all.

The candidate course detail view now renders an explicit unavailable message instead of silently falling back to the French lesson body in Arabic mode. The list view was also upgraded to use localized titles and descriptions when available so course browsing aligns better with the selected language.

Feature coverage in `CourseLocalizationTest` now proves the candidate page renders Arabic lesson text when available and shows the unavailable-state message — while hiding the French description/body — when Arabic content is missing. Locale regression coverage remained green, and the production asset build still succeeds.

## Deviations

None.

## Files Created/Modified

- `app/Http/Controllers/CandidateCourseController.php` — locale-aware course text preparation and unavailable-state flag
- `resources/views/candidate/courses/index.blade.php` — localized course card text
- `resources/views/candidate/courses/show.blade.php` — explicit Arabic unavailable state and locale-aware lesson rendering
- `lang/fr/ui.php` / `lang/ar/ui.php` — unavailable-state copy
- `tests/Feature/CourseLocalizationTest.php` — candidate-side bilingual rendering verification
