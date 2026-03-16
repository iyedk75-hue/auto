---
estimated_steps: 6
estimated_files: 5
---

# T02: Add authenticated inline asset endpoints and wire the viewer to them

**Slice:** S03 — Protected inline learning viewer
**Milestone:** M001

## Description

Deliver lesson PDFs and videos through authenticated inline routes and replace direct public asset URLs on the candidate course page.

## Steps

1. Add authenticated media and PDF routes for lesson assets.
2. Resolve the correct storage disk/path for each asset through the new compatibility contract.
3. Return inline file responses suitable for browser playback/viewing.
4. Update the candidate course page to use protected route URLs for media/PDF.
5. Update any necessary admin preview links so protected storage does not break course management.
6. Add route-level feature tests for guest denial and authenticated success.

## Must-Haves

- [ ] Candidate lesson pages consume authenticated asset routes instead of public storage URLs.
- [ ] Guests cannot access protected lesson assets.

## Verification

- `php artisan test --filter=CourseProtectionTest`
- Assert lesson HTML no longer contains `/storage/courses/...` for protected media/PDF.

## Inputs

- `.gsd/milestones/M001/slices/S03/tasks/T01-PLAN.md` — protected storage contract
- `routes/web.php` — current course PDF route surface
- `app/Http/Controllers/CandidateCourseController.php` — current candidate PDF delivery and public media URL use

## Expected Output

- `routes/web.php` — authenticated lesson media/pdf routes
- `app/Http/Controllers/CandidateCourseController.php` — inline protected asset responses
- `resources/views/candidate/courses/show.blade.php` — protected route-based viewer wiring
