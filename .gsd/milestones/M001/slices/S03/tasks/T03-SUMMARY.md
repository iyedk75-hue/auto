---
id: T03
parent: S03
milestone: M001
provides:
  - Visible protected-viewer notice on the candidate lesson page
  - Client-side blocking for right-click and common save/copy/devtools shortcuts on protected lesson pages
  - Protected-viewer feedback surface for blocked actions
  - Video deterrence attributes such as `controlsList="nodownload noplaybackrate"` and disabled picture-in-picture
  - Frontend build verification for the deterrence layer
requires:
  - slice: S03
    provides: Authenticated route-based lesson viewer from T02
affects: [S05]
key_files:
  - resources/views/candidate/courses/show.blade.php
  - resources/js/app.js
  - resources/css/app.css
  - lang/fr/ui.php
  - lang/ar/ui.php
  - tests/Feature/CourseProtectionTest.php
key_decisions:
  - "Deterrence is visible and honest: the page tells the student what is blocked instead of silently preventing actions."
patterns_established:
  - "Protected viewer behavior is activated by page-level data attributes rather than global site-wide blocking."
drill_down_paths:
  - .gsd/milestones/M001/slices/S03/tasks/T03-PLAN.md
duration: 25m
verification_result: pass
completed_at: 2026-03-15T08:45:00Z
---

# T03: Add deterrence behavior and protected-viewer messaging

**The lesson page now visibly presents itself as protected and intercepts right-click and common save/copy shortcuts without pretending to be unbreakable DRM.**

## What Happened

Added translated protection messaging to the course page and marked the lesson surface with explicit protection data attributes. The page now includes a visible notice explaining that viewing is online-only and that some actions are blocked.

`resources/js/app.js` now activates deterrence only on protected lesson pages. It blocks right-click, copy/cut, drag-start, common save/copy/print/source shortcuts, and common devtools shortcuts, while surfacing a short feedback message instead of failing silently. The video element now carries `controlsList="nodownload noplaybackrate"` and disables picture-in-picture where supported.

The protection suite was extended to assert the presence of the protected-viewer hooks and messaging, and the frontend build passed after the JS/CSS changes.

## Deviations

None.

## Files Created/Modified

- `resources/views/candidate/courses/show.blade.php` — protected-viewer notice, attributes, and deterrence markup
- `resources/js/app.js` — page-scoped deterrence handlers and feedback behavior
- `resources/css/app.css` — feedback surface styling
- `lang/fr/ui.php` / `lang/ar/ui.php` — protection notice and feedback copy
- `tests/Feature/CourseProtectionTest.php` — protected-viewer markup verification
