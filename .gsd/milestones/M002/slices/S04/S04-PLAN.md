# S04: Protected resource delivery and final integration

**Goal:** Preserve protected per-resource delivery and close M002 with a real admin-to-candidate multi-resource course proof.
**Demo:** An admin authors a course with an ordered note, video, and PDF resource, then a candidate opens that course, switches supports inside the same page, and protected file resources still require authenticated access.

## Must-Haves

- Child video/PDF resources are delivered through authenticated routes, not public storage URLs.
- Candidate pages keep deterrence and private-route rendering for file resources inside the new multi-resource flow.
- The final integrated proof covers admin authoring plus candidate consumption of note, video, and PDF resources.
- Legacy single-resource courses still remain usable after the assembled flow is introduced.

## Proof Level

- This slice proves: final-assembly
- Real runtime required: yes
- Human/UAT required: yes

## Verification

- `php artisan test --filter=AdminCourseResourceTest`
- `php artisan test --filter=CandidateCourseResourceViewTest`
- `php artisan test --filter=CourseProtectionTest`
- `php artisan test --filter=MilestoneIntegrationTest`
- `npm run build`
- Browser-check the candidate course page at `http://172.28.224.1:8000`

## Observability / Diagnostics

- Runtime signals: candidate pages expose selected-resource state, file-resource routes return inline headers, and browser-visible viewer state stays on the same course page
- Inspection surfaces: `courses.resources.file`, candidate course HTML, protection/integration tests, and the running browser session
- Failure visibility: leaked storage URLs, broken inline headers, missing mixed-resource order, or candidate viewer regressions
- Redaction constraints: protected file paths remain internal and must not be surfaced directly in HTML

## Integration Closure

- Upstream surfaces consumed: S02 admin resource authoring flow, S03 candidate classroom list/viewer, M001 protected viewing baseline
- New wiring introduced in this slice: final assembled admin-authored mixed-resource proof and route-level protection coverage for child resources
- What remains before the milestone is truly usable end-to-end: nothing

## Tasks

- [x] **T01: Finalize protected child-resource delivery and regression coverage** `est:45m`
  - Why: The candidate viewer is only shippable if child file resources keep the same private authenticated delivery guarantees as the old course-level routes.
  - Files: `app/Http/Controllers/CandidateCourseController.php`, `routes/web.php`, `tests/Feature/CourseProtectionTest.php`
  - Do: Add the protected child-resource file route, enforce authenticated delivery, and extend protection regressions so candidate HTML and file responses prove no public storage leakage.
  - Verify: `php artisan test --filter=CourseProtectionTest`
  - Done when: Child video/PDF resources load through authenticated inline routes and the protection suite covers both legacy and child-resource flows.
- [x] **T02: Add final admin-authored mixed-resource integration proof** `est:1h`
  - Why: M002 is not complete until admin authoring and candidate same-page viewing are exercised together through the real route/controller stack.
  - Files: `tests/Feature/MilestoneIntegrationTest.php`, `tests/Feature/AdminCourseResourceTest.php`
  - Do: Prove an admin can create a course, add note/video/PDF resources, and that a candidate can consume them in order through the same-page viewer and protected file routes.
  - Verify: `php artisan test --filter=MilestoneIntegrationTest && php artisan test --filter=AdminCourseResourceTest`
  - Done when: A single end-to-end test covers the assembled multi-resource flow from admin authoring to candidate protected consumption.
- [x] **T03: Browser-check the final classroom flow and close the milestone** `est:45m`
  - Why: The milestone needs a real browser pass and clean artifact closure, not just passing backend tests.
  - Files: `.gsd/milestones/M002/*`, `.gsd/STATE.md`, `auto/.gsd/PROJECT.md`
  - Do: Verify the running candidate page shows the classroom feed and same-page viewer, record any non-blocking runtime observations, then write slice/milestone summaries and mark M002 complete.
  - Verify: `npm run build` plus live browser assertions against the running app
  - Done when: The classroom flow is browser-checked and all milestone artifacts reflect completion truthfully.

## Files Likely Touched

- `app/Http/Controllers/CandidateCourseController.php`
- `routes/web.php`
- `tests/Feature/CourseProtectionTest.php`
- `tests/Feature/MilestoneIntegrationTest.php`
- `.gsd/milestones/M002/slices/S04/*`
- `.gsd/milestones/M002/M002-SUMMARY.md`
- `.gsd/PROJECT.md`
- `.gsd/STATE.md`
