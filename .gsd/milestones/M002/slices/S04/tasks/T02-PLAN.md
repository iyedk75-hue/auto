---
estimated_steps: 5
estimated_files: 2
---

# T02: Add final admin-authored mixed-resource integration proof

**Slice:** S04 — Protected resource delivery and final integration
**Milestone:** M002

## Description

Prove the whole milestone by running one assembled flow from admin authoring through candidate viewing for note, video, and PDF resources.

## Steps

1. Create a course through the real admin course route.
2. Add note, video, and PDF resources through the real nested admin resource routes.
3. Assert protected files persist on the private disk.
4. Open the course as a candidate and assert same-page selection plus protected file route rendering.
5. Verify guest access is denied after logout.

## Must-Haves

- [x] The final proof covers note, video, and PDF resources together.
- [x] The proof crosses admin creation, candidate viewing, and protected file access boundaries.

## Verification

- `php artisan test --filter=MilestoneIntegrationTest && php artisan test --filter=AdminCourseResourceTest`
- The milestone test proves admin authoring and candidate consumption in one flow.

## Inputs

- `routes/web.php` — real admin and candidate entrypoints
- `tests/Feature/AdminCourseResourceTest.php` — admin resource CRUD regression coverage

## Expected Output

- `tests/Feature/MilestoneIntegrationTest.php` — assembled mixed-resource end-to-end proof
- `tests/Feature/AdminCourseResourceTest.php` — supporting admin workflow regression still green
