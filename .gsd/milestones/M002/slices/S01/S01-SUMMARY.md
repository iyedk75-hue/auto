---
id: S01
parent: M002
milestone: M002
provides:
  - `course_resources` child model and schema for repeated supports
  - explicit support types: video, pdf, note
  - deterministic manual resource ordering contract
  - normalized resource resolution with legacy single-resource fallback
  - downstream-ready locale-aware resource payload for later admin/student slices
requires:
  - milestone: M001
    provides: bilingual course model, protected asset patterns, and same-page lesson foundation
affects: [S02, S03, S04]
key_files:
  - database/migrations/2026_03_15_000015_create_course_resources_table.php
  - app/Models/Course.php
  - app/Models/CourseResource.php
  - tests/Feature/CourseResourceModelTest.php
  - tests/Feature/CourseTransitionTest.php
key_decisions:
  - "M002 uses a child resource model rather than expanding `courses` with more repeated file columns."
  - "Resource titles and note bodies are bilingual from the start, matching the existing bilingual course model."
  - "Legacy single-resource courses resolve into synthetic resource items until later slices move the UI fully onto the child model."
patterns_established:
  - "Downstream resource consumption should go through `resolvedResources()` rather than direct field inspection."
  - "Locale-aware list/viewer rendering should consume the normalized display fields from resolved resource payloads."
drill_down_paths:
  - .gsd/milestones/M002/slices/S01/tasks/T01-SUMMARY.md
  - .gsd/milestones/M002/slices/S01/tasks/T02-SUMMARY.md
  - .gsd/milestones/M002/slices/S01/tasks/T03-SUMMARY.md
verification_result: pass
completed_at: 2026-03-15T17:10:00Z
---

# S01: Resource model and transition layer

**Courses can now represent many ordered resources, and older single-resource courses still resolve through the same normalized contract.**

## What Happened

S01 introduced the structural foundation for multi-resource courses by creating `course_resources` as a repeated child model. This replaced the old assumption that a course only has one media slot and one PDF slot.

The slice also solved the hardest transition problem up front: older courses still using `media_path` and `pdf_path` now resolve into synthetic resource items, while new child-resource-backed courses resolve from the new model and take precedence cleanly. Downstream slices no longer have to inspect both schemas directly.

Finally, the normalized resource payload was made locale-aware, so later admin/student UI work can use `display_title` and `display_note_body` directly without rebuilding bilingual selection logic for every support item.

## Deviations

None.

## Files Created/Modified

- `database/migrations/2026_03_15_000015_create_course_resources_table.php` — repeated child-resource schema
- `app/Models/CourseResource.php` — resource model, types, locale helpers, normalized payload output
- `app/Models/Course.php` — ordered relation and normalized legacy/new resource resolution
- `tests/Feature/CourseResourceModelTest.php` — child-resource schema/model verification
- `tests/Feature/CourseTransitionTest.php` — transition and normalized-payload verification
