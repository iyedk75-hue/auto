---
estimated_steps: 5
estimated_files: 5
---

# T03: Add deterrence behavior and protected-viewer messaging

**Slice:** S03 — Protected inline learning viewer
**Milestone:** M001

## Description

Add honest deterrence features to the course viewer so right-click and common save/copy shortcuts are blocked and the student sees protected-viewer messaging.

## Steps

1. Add translated warning copy for the protected viewer.
2. Mark the protected course page with the selectors/data attributes needed for viewer behavior.
3. Add client-side handlers blocking right-click and common save/copy shortcuts on the course page.
4. Add media-element deterrence attributes such as `controlsList="nodownload"` where supported.
5. Verify the page markup and frontend build remain healthy.

## Must-Haves

- [ ] The protected lesson page shows visible protection messaging.
- [ ] Right-click and common save/copy shortcuts are intercepted on the protected lesson page.

## Verification

- `php artisan test --filter=CourseProtectionTest`
- `npm run build`

## Inputs

- `.gsd/milestones/M001/slices/S03/tasks/T02-PLAN.md` — authenticated viewer route wiring
- `resources/js/app.js` — current frontend bootstrap
- `resources/views/candidate/courses/show.blade.php` — protected lesson page surface

## Expected Output

- `resources/js/app.js` — deterrence handlers for the protected viewer
- `resources/views/candidate/courses/show.blade.php` — protected-viewer warning surface and attributes
- `tests/Feature/CourseProtectionTest.php` — deterrence markup verification
