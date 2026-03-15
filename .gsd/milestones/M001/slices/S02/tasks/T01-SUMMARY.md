---
id: T01
parent: S02
milestone: M001
provides:
  - Arabic title/description/content columns on `courses`
  - Locale-aware course text selection helpers on the Course model
  - Explicit Arabic-translation presence detection for lesson text
  - Focused feature coverage for French, Arabic, and missing-Arabic model behavior
requires:
  - slice: S01
    provides: Locale switching and translated classroom shell from S01
affects: [S03, S05]
key_files:
  - database/migrations/2026_03_15_000014_add_arabic_content_to_courses_table.php
  - app/Models/Course.php
  - tests/Feature/CourseLocalizationTest.php
key_decisions:
  - "Existing `title` / `description` / `content` remain the French source of truth; Arabic lives in parallel `_ar` columns."
  - "The model exposes locale-aware selection helpers so admin and candidate flows can share one bilingual contract."
patterns_established:
  - "Bilingual course text is selected through model methods (`titleForLocale`, `descriptionForLocale`, `contentForLocale`) rather than repeated controller/view conditionals."
drill_down_paths:
  - .gsd/milestones/M001/slices/S02/tasks/T01-PLAN.md
duration: 35m
verification_result: pass
completed_at: 2026-03-15T07:45:00Z
---

# T01: Extend the course schema for bilingual text content

**Courses can now carry French and Arabic text in parallel, with tests proving the locale-selection contract that later slices will consume.**

## What Happened

Added a migration that extends `courses` with `title_ar`, `description_ar`, and `content_ar`. The existing `title`, `description`, and `content` columns remain the French track, which avoids a disruptive rename and keeps current records intact.

The `Course` model now registers the new fields as mass-assignable and exposes a small bilingual API: `titleForLocale`, `descriptionForLocale`, `contentForLocale`, and `hasArabicTranslation`. This establishes one shared contract for later admin and candidate work instead of duplicating locale-selection logic in controllers and Blade views.

Verification ran in the Windows-side PHP copy: the new migration applied cleanly there, and `CourseLocalizationTest` passed for French selection, Arabic selection, and explicit missing-Arabic detection.

## Deviations

None.

## Files Created/Modified

- `database/migrations/2026_03_15_000014_add_arabic_content_to_courses_table.php` — Arabic text columns for courses
- `app/Models/Course.php` — bilingual field registration and locale-aware content helpers
- `tests/Feature/CourseLocalizationTest.php` — proof for French/Arabic content selection behavior
