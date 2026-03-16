# S03: Student classroom-style resource list

**Goal:** Give candidates a Classroom-style stacked support list inside the course page and open the selected support below the list without leaving the page.
**Demo:** A candidate opens a course, sees ordered support cards with type/date metadata, clicks another support, and the viewer below switches in place to the selected note, video, or PDF.

## Must-Haves

- Candidate course pages consume the normalized `resolvedResources()` contract instead of direct `media_path` / `pdf_path` assumptions.
- The support list is stacked and visually closer to the Classroom reference than the old single-media layout.
- Each support item shows type and date metadata.
- The selected support opens below the list on the same course page.
- Legacy single-resource courses still render through the same list/viewer flow.
- FR/AR and RTL behavior remain intact.

## Proof Level

- This slice proves: integration
- Real runtime required: yes
- Human/UAT required: yes

## Verification

- `php artisan test --filter=CandidateCourseResourceViewTest`
- `php artisan test --filter=CourseProtectionTest`
- `npm run build`
- Browser-check the candidate course page at `http://172.28.224.1:8000`

## Observability / Diagnostics

- Runtime signals: the selected support is visually marked in the list and the page exposes the selected resource key in rendered state
- Inspection surfaces: `courses.show` HTML, query-string resource selection, protected resource route responses, browser-visible stacked list/viewer state
- Failure visibility: wrong selected viewer, missing type/date metadata, missing protected viewer URL, or legacy courses collapsing back to the old one-media layout
- Redaction constraints: protected file paths stay behind authenticated routes; no direct storage URLs in candidate HTML

## Integration Closure

- Upstream surfaces consumed: `Course::resolvedResources()`, existing course localization helpers, existing protected-viewer deterrence shell
- New wiring introduced in this slice: candidate resource selection contract, stacked list Blade composition, in-page viewer switching, and resource-level viewer URL mapping
- What remains before the milestone is truly usable end-to-end: final assembled regression/integration proof for mixed note/video/PDF authoring and protected per-resource delivery

## Tasks

- [x] **T01: Add candidate resource-selection contract and feature proof** `est:1h`
  - Why: The student page needs a stable selected-resource contract before the UI can switch supports safely.
  - Files: `app/Http/Controllers/CandidateCourseController.php`, `routes/web.php`, `tests/Feature/CandidateCourseResourceViewTest.php`
  - Do: Expose ordered resource list metadata and selected-resource state to the candidate page, support query-string selection with safe fallback, and map resource viewer URLs so downstream Blade can render note/video/PDF views from one contract.
  - Verify: `php artisan test --filter=CandidateCourseResourceViewTest`
  - Done when: The candidate page receives an ordered, selectable resource list and tests prove default selection, explicit selection, and legacy compatibility.
- [x] **T02: Build the classroom-style stacked list and same-page viewer** `est:1h30m`
  - Why: The old single-media layout does not satisfy the Classroom-style stacked flow the user asked for.
  - Files: `resources/views/candidate/courses/show.blade.php`, `resources/css/app.css`, `lang/fr/ui.php`, `lang/ar/ui.php`
  - Do: Replace the single-media blocks with stacked support cards inspired by the reference, show type/date metadata on each item, keep the viewer below the list, and preserve bilingual/RTL behavior with Alpine-enhanced in-page switching.
  - Verify: `php artisan test --filter=CandidateCourseResourceViewTest && npm run build`
  - Done when: Candidates can switch supports inside the course page and the viewer changes below the list without returning to the old single-resource layout.
- [x] **T03: Prove legacy/new candidate flows and live classroom behavior** `est:1h`
  - Why: This slice is only real when both admin-authored multi-resource courses and older single-resource courses behave correctly in the browser.
  - Files: `tests/Feature/CourseProtectionTest.php`, `tests/Feature/MilestoneIntegrationTest.php`, `.gsd/milestones/M002/slices/S03/*`
  - Do: Extend protection/integration coverage for the new candidate viewer path, verify no public storage URLs leak, then browser-check the live course page against the reference feel and same-page switching behavior.
  - Verify: `php artisan test --filter=CourseProtectionTest && php artisan test --filter=MilestoneIntegrationTest`
  - Done when: Legacy and new courses both render through the stacked list/viewer flow and the live browser check confirms the intended same-page interaction.

## Files Likely Touched

- `routes/web.php`
- `app/Http/Controllers/CandidateCourseController.php`
- `resources/views/candidate/courses/show.blade.php`
- `resources/css/app.css`
- `lang/fr/ui.php`
- `lang/ar/ui.php`
- `tests/Feature/CandidateCourseResourceViewTest.php`
- `tests/Feature/CourseProtectionTest.php`
- `tests/Feature/MilestoneIntegrationTest.php`
