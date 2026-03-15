---
estimated_steps: 5
estimated_files: 3
---

# T03: Expose a downstream-ready resource shape for later slices

**Slice:** S01 — Resource model and transition layer
**Milestone:** M002

## Description

Add the normalized locale-aware resource shape that S02 and S03 will consume for admin management and student rendering.

## Steps

1. Add locale-aware title/body helpers for note and file resources.
2. Expose one normalized ordered resource payload shape from the model layer.
3. Include support for file metadata and note content in that shape.
4. Extend transition tests to assert the downstream-ready payload fields.
5. Run transition and milestone integration regression tests.

## Must-Haves

- [ ] Downstream slices can consume one consistent ordered resource shape.
- [ ] The resource shape supports both note resources and file resources.

## Verification

- `php artisan test --filter=CourseTransitionTest && php artisan test --filter=MilestoneIntegrationTest`

## Inputs

- `.gsd/milestones/M002/slices/S01/tasks/T02-PLAN.md` — normalized legacy/new resource resolution contract
- M001 bilingual content and protected asset patterns

## Expected Output

- `app/Models/CourseResource.php` and/or `app/Models/Course.php` — downstream-ready normalized resource payload helpers
- `tests/Feature/CourseTransitionTest.php` — payload-shape verification
