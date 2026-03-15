---
id: T03
parent: S01
milestone: M001
provides:
  - Localized public landing page shell in French and Arabic
  - Localized candidate dashboard and classroom shell labels
  - Localized admin dashboard entry surface and quick-action panels
  - Locale-aware course category labels through the Course model
  - Additional dashboard/localization feature assertions and production build proof
requires:
  - slice: S01
    provides: Locale middleware, translated shell, and switcher contract from T01 and T02
affects: [S02, S03, S04, S05]
key_files:
  - lang/fr/ui.php
  - lang/ar/ui.php
  - app/Models/Course.php
  - resources/views/marketing/massar.blade.php
  - resources/views/candidate/dashboard.blade.php
  - resources/views/admin/dashboard.blade.php
  - resources/views/candidate/courses/index.blade.php
  - resources/views/candidate/courses/show.blade.php
  - tests/Feature/LocaleSwitchTest.php
  - tests/Feature/AdminAccessTest.php
key_decisions:
  - "Course category labels resolve through translation keys so admin and candidate course surfaces share one locale-aware source."
  - "When browser verification is blocked by the runtime boundary, use the strongest available substitutes: production asset build plus feature tests asserting translated copy."
patterns_established:
  - "Dashboards and classroom shell text read from `ui.*` keys rather than hard-coded copy."
  - "Feature tests for localized pages assert both shell direction and translated visible text."
drill_down_paths:
  - .gsd/milestones/M001/slices/S01/tasks/T03-PLAN.md
duration: 1h 15m
verification_result: pass-with-environment-note
completed_at: 2026-03-15T07:20:00Z
---

# T03: Localize core dashboards and classroom shell

**The public landing page, the admin/candidate dashboards, and the course shell now render localized French/Arabic UI copy on the same locale foundation.**

## What Happened

Expanded `ui.php` translations to cover the main public, admin, candidate, and classroom surfaces, then rewrote the first user-facing entry views to use those keys instead of hard-coded French or English strings. The candidate dashboard now translates status labels, quiz callouts, and payment status text; the admin dashboard uses localized section and quick-action labels; and the course list/detail shell uses localized labels for titles, metadata, and quick-info blocks.

`Course::categoryLabels()` now resolves through translation keys, which gives later slices one locale-aware category surface shared by both admin and candidate workflows. Tests were strengthened so locale checks now assert visible Arabic dashboard/home copy, not only `lang` and `dir` attributes.

A live browser pass was attempted against a real Laravel server in the Windows-side verification copy, but this environment could not route browser or curl traffic to that Windows-served PHP process. Because of that runtime boundary, verification for this task used the strongest reachable substitutes: a production Vite build plus the relevant Laravel feature suites, all passing.

## Deviations

Browser verification was attempted but blocked by environment networking between the browser harness / WSL shell and the Windows-hosted XAMPP PHP server. This was treated as an environment note rather than an application failure because the feature suites and build succeeded in the same verified code copy.

## Files Created/Modified

- `lang/fr/ui.php` / `lang/ar/ui.php` — expanded with public, dashboard, and classroom shell translations
- `app/Models/Course.php` — category labels now resolve through translation keys
- `resources/views/marketing/massar.blade.php` — localized public landing shell
- `resources/views/candidate/dashboard.blade.php` — localized candidate dashboard
- `resources/views/admin/dashboard.blade.php` — localized admin dashboard
- `resources/views/candidate/courses/index.blade.php` — localized course list shell
- `resources/views/candidate/courses/show.blade.php` — localized course detail shell
- `tests/Feature/LocaleSwitchTest.php` — asserts translated public/candidate/admin Arabic copy
- `tests/Feature/AdminAccessTest.php` — asserts Arabic admin dashboard copy
