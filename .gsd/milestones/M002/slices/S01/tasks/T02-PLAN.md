---
estimated_steps: 6
estimated_files: 4
---

# T02: Add normalized resource resolution with legacy compatibility

**Slice:** S01 — Resource model and transition layer
**Milestone:** M002

## Description

Teach courses to resolve their effective resource list from child resources or, when needed, from the existing legacy file fields so old courses still work during transition.

## Steps

1. Add normalized resource resolution helpers on `Course` and/or `CourseResource`.
2. Prefer real child resources when they exist.
3. Fall back to synthetic legacy resources generated from `media_path` / `pdf_path` when no child resources exist.
4. Include stable type/order/origin metadata in the normalized result.
5. Add transition tests for legacy-only and child-resource-backed courses.
6. Run the transition test suite and fix any compatibility gaps.

## Must-Haves

- [ ] Legacy single-resource courses still expose a usable resource list.
- [ ] New multi-resource courses resolve only from child resources when those exist.

## Verification

- `php artisan test --filter=CourseTransitionTest`
- Confirm normalized resource output includes deterministic ordering and origin/type info.

## Inputs

- `.gsd/milestones/M002/slices/S01/tasks/T01-PLAN.md` — child resource schema/model contract
- `app/Models/Course.php` — current legacy file fields and protected asset helpers

## Expected Output

- `app/Models/Course.php` — effective resource resolution helpers
- `tests/Feature/CourseTransitionTest.php` — legacy compatibility verification
