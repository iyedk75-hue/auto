# S03: Protected inline learning viewer

**Goal:** Move lesson PDFs and videos behind authenticated/private delivery and add honest deterrence protections to the inline course viewer.
**Demo:** A candidate opens one course page and reads the PDF or watches the video inline through authenticated routes with no public asset URLs, no download CTA, and visible deterrence behavior.

## Must-Haves

- New course media/PDF uploads are stored on non-public storage.
- Existing public course assets still work through a compatibility path so current records do not break.
- Candidate lesson pages use authenticated inline routes for media/PDF instead of `Storage::url(...)` public paths.
- The viewer exposes deterrence protections such as blocked right click, blocked common save/copy shortcuts, and visible protection messaging.
- Protected routes deny unauthenticated access.
- Slice outputs match the S03 → S05 boundary-map contract for authenticated asset endpoints and protected viewer behavior.

## Proof Level

- This slice proves: integration
- Real runtime required: yes
- Human/UAT required: no

## Verification

- `php artisan test --filter=CourseProtectionTest`
- `php artisan test --filter=AdminCourseTest`
- `php artisan test --filter=CourseLocalizationTest`
- `npm run build`

## Observability / Diagnostics

- Runtime signals: authenticated asset routes return inline responses; unauthenticated requests redirect/deny; protected page markup exposes deterrence state and warning copy
- Inspection surfaces: feature tests, route responses and headers, rendered course HTML, storage disk assertions in tests
- Failure visibility: broken storage wiring appears as missing asset route responses, public URLs leaking into HTML, or test failures on disk assertions and auth checks
- Redaction constraints: never log or expose secret asset paths beyond normal application responses

## Integration Closure

- Upstream surfaces consumed: S01 locale-aware classroom shell and S02 bilingual course content contract
- New wiring introduced in this slice: protected asset storage selection, authenticated media/pdf routes, viewer deterrence JS/CSS, admin preview compatibility
- What remains before the milestone is truly usable end-to-end: landing cleanup and final cross-surface verification

## Tasks

- [x] **T01: Move lesson asset persistence to a protected storage contract** `est:1h`
  - Why: Public storage URLs undermine the requested protection and make later viewer work mostly cosmetic.
  - Files: `app/Http/Controllers/AdminCourseController.php`, `app/Models/Course.php`, `tests/Feature/CourseProtectionTest.php`, `config/filesystems.php`
  - Do: Store new course media/PDF files on private storage, add path-resolution helpers or compatibility logic so legacy public assets still resolve, and add tests proving the storage contract for create/update flows.
  - Verify: `php artisan test --filter=CourseProtectionTest`
  - Done when: New uploads land on protected storage and legacy assets still have a deterministic access path.
- [x] **T02: Add authenticated inline asset endpoints and wire the viewer to them** `est:1h`
  - Why: Candidates should consume lesson files through app-controlled routes, not direct public URLs.
  - Files: `routes/web.php`, `app/Http/Controllers/CandidateCourseController.php`, `resources/views/candidate/courses/show.blade.php`, `resources/views/admin/courses/partials/form.blade.php`, `tests/Feature/CourseProtectionTest.php`
  - Do: Add protected media endpoints, keep PDF inline delivery, update the candidate lesson page and any needed admin previews to use authenticated routes, and verify guest denial plus candidate success.
  - Verify: `php artisan test --filter=CourseProtectionTest`
  - Done when: Candidate lesson HTML contains protected route URLs instead of public storage URLs and unauthenticated access is denied.
- [x] **T03: Add deterrence behavior and protected-viewer messaging** `est:45m`
  - Why: The user explicitly asked for right-click/save deterrence in addition to private delivery.
  - Files: `resources/views/candidate/courses/show.blade.php`, `resources/js/app.js`, `resources/css/app.css`, `lang/fr/ui.php`, `lang/ar/ui.php`, `tests/Feature/CourseProtectionTest.php`
  - Do: Add viewer-level warning copy, block right-click and common save/copy shortcuts on the protected course page, use media attributes like `controlsList="nodownload"` where applicable, and verify the expected markup and messaging are present.
  - Verify: `php artisan test --filter=CourseProtectionTest && npm run build`
  - Done when: The course page exposes the deterrence UI/markup and no download CTA is present in the protected viewer.

## Files Likely Touched

- `app/Http/Controllers/AdminCourseController.php`
- `app/Http/Controllers/CandidateCourseController.php`
- `app/Models/Course.php`
- `routes/web.php`
- `resources/views/candidate/courses/show.blade.php`
- `resources/views/admin/courses/partials/form.blade.php`
- `resources/js/app.js`
- `resources/css/app.css`
- `lang/fr/ui.php`
- `lang/ar/ui.php`
- `tests/Feature/CourseProtectionTest.php`
