---
estimated_steps: 5
estimated_files: 3
---

# T01: Finalize protected child-resource delivery and regression coverage

**Slice:** S04 — Protected resource delivery and final integration
**Milestone:** M002

## Description

Close the protection gap created by child resources so file supports keep the same authenticated inline delivery guarantees as the old course-level media/PDF paths.

## Steps

1. Add a candidate-facing route for child resource file delivery.
2. Enforce course/resource ownership and authenticated access checks in `CandidateCourseController`.
3. Reuse inline response headers and deterrence expectations from the M001 protected viewer baseline.
4. Extend protection tests for guest denial, authenticated access, and rendered HTML without leaked storage URLs.
5. Re-run the protection suite until both legacy and child-resource flows pass.

## Must-Haves

- [x] Child file resources are served through authenticated inline routes.
- [x] Candidate HTML does not expose public storage URLs for child resources.

## Verification

- `php artisan test --filter=CourseProtectionTest`
- Guest denial and authenticated inline headers both pass for child resources.

## Observability Impact

- Signals added/changed: child-resource file routes now return explicit inline headers and candidate HTML exposes selected-resource state instead of public paths
- How a future agent inspects this: `CourseProtectionTest` plus loading `courses.show?resource=...` for a child file resource
- Failure state exposed: leaked storage URLs, broken inline headers, or unauthorized file access

## Inputs

- `app/Models/CourseResource.php` — file-resource type and protected asset disk resolution
- `tests/Feature/CourseProtectionTest.php` — existing protection baseline from M001

## Expected Output

- `app/Http/Controllers/CandidateCourseController.php` — child-resource file delivery
- `routes/web.php` — protected child-resource route
- `tests/Feature/CourseProtectionTest.php` — updated protection regressions
