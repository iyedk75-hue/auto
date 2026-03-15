---
estimated_steps: 6
estimated_files: 4
---

# T03: Prove legacy/new candidate flows and live classroom behavior

**Slice:** S03 — Student classroom-style resource list
**Milestone:** M002

## Description

Close the slice by proving the new candidate page works for both child-resource courses and legacy single-resource courses, then record the slice state cleanly.

## Steps

1. Extend protection tests to assert the new candidate viewer path still uses authenticated URLs and does not leak direct storage paths.
2. Extend milestone integration coverage so the candidate page asserts list/viewer behavior instead of the old single-media assumptions.
3. Browser-check the live candidate page at `http://172.28.224.1:8000` using the running Windows-hosted server.
4. Compare the rendered list against the Classroom-style reference and correct any obvious structural mismatch.
5. Write S03 summary and UAT artifacts once verification passes.
6. Update milestone state and roadmap to move toward S04.

## Must-Haves

- [ ] Legacy and child-resource-backed courses both render through the stacked list/viewer path.
- [ ] Protection assertions still prove authenticated URLs instead of public storage leakage.

## Verification

- `php artisan test --filter=CourseProtectionTest`
- `php artisan test --filter=MilestoneIntegrationTest`
- Live browser verification against the running app

## Observability Impact

- Signals added/changed: browser-visible selected support state and updated protection assertions around rendered viewer URLs
- How a future agent inspects this: feature tests plus the live course page on the running `laravel-winhost-server`
- Failure state exposed: broken viewer state, leaked storage URLs, or list/viewer regressions on legacy courses

## Inputs

- `tests/Feature/CourseProtectionTest.php` — existing protected-viewing regression surface
- `tests/Feature/MilestoneIntegrationTest.php` — milestone-level assembled flow proof

## Expected Output

- `tests/Feature/CourseProtectionTest.php` — updated candidate viewer protection assertions
- `tests/Feature/MilestoneIntegrationTest.php` — updated assembled-course viewer proof
- `.gsd/milestones/M002/slices/S03/S03-SUMMARY.md` — slice summary after passing verification
- `.gsd/milestones/M002/slices/S03/S03-UAT.md` — human verification notes
