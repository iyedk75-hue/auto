---
estimated_steps: 4
estimated_files: 3
---

# T03: Surface resource counts and transition-safe admin behavior

**Slice:** S02 — Admin multi-resource management
**Milestone:** M002

## Description

Make the course admin surfaces clearly reflect the new resource workflow and stay safe for older legacy courses during the transition.

## Steps

1. Add resource counts and management entry points to the course listing or edit flow.
2. Make legacy-only courses visibly manageable without forcing immediate migration.
3. Extend tests for mixed legacy/new admin scenarios.
4. Re-run admin resource and transition suites.

## Must-Haves

- [ ] Admin can see which courses already have child resources.
- [ ] Legacy single-resource courses still have a clear path into the new resource workflow.

## Verification

- `php artisan test --filter=AdminCourseResourceTest && php artisan test --filter=CourseTransitionTest`

## Inputs

- `.gsd/milestones/M002/slices/S02/tasks/T02-PLAN.md` — admin resource UI contract
- `.gsd/milestones/M002/slices/S01/S01-SUMMARY.md` — transition behavior for legacy courses

## Expected Output

- updated admin course surfaces showing resource management state
- stronger admin tests for legacy/new course management behavior
