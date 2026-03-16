---
id: S01
parent: M001
milestone: M001
provides:
  - Shared French/Arabic locale switching across public, candidate, and admin web flows
  - RTL-aware layout shell with translated navigation and auth entry screens
  - Localized public landing, candidate dashboard, admin dashboard, and classroom shell views
  - Reusable locale switcher component and locale-aware course category labels
  - Feature-test coverage for locale switching, Arabic login screens, and Arabic admin/dashboard copy
requires: []
affects: [S02, S03, S04, S05]
key_files:
  - app/Http/Middleware/SetLocale.php
  - routes/web.php
  - lang/fr/ui.php
  - lang/ar/ui.php
  - resources/views/components/locale-switcher.blade.php
  - resources/views/layouts/navigation.blade.php
  - resources/views/layouts/guest.blade.php
  - resources/views/marketing/massar.blade.php
  - resources/views/candidate/dashboard.blade.php
  - resources/views/admin/dashboard.blade.php
  - resources/views/candidate/courses/index.blade.php
  - resources/views/candidate/courses/show.blade.php
key_decisions:
  - "Locale state is owned by web middleware with session/cookie persistence."
  - "App-specific shell copy lives in `lang/*/ui.php`; existing Breeze-style strings use locale JSON files."
  - "RTL support is implemented through `dir`, Arabic typography, and targeted shell CSS adjustments instead of a view fork."
patterns_established:
  - "New UI copy should resolve through translation keys, not hard-coded strings."
  - "Shared locale-switch behavior is delivered via one route and one Blade component."
  - "Localized page tests should assert visible copy as well as `lang` / `dir`."
drill_down_paths:
  - .gsd/milestones/M001/slices/S01/tasks/T01-SUMMARY.md
  - .gsd/milestones/M001/slices/S01/tasks/T02-SUMMARY.md
  - .gsd/milestones/M001/slices/S01/tasks/T03-SUMMARY.md
verification_result: pass-with-environment-note
completed_at: 2026-03-15T07:22:00Z
---

# S01: Language foundation and RTL shell

**The app now has a real bilingual shell: locale switching, RTL behavior, translated navigation/auth, and localized public/admin/candidate entry surfaces are all wired and tested.**

## What Happened

S01 established the language foundation that the rest of the milestone depends on. Locale state now flows through middleware instead of ad hoc controller logic, and a shared switch endpoint plus reusable switcher component makes French/Arabic behavior consistent for guests, candidates, and admins.

The slice also replaced the mixed hard-coded shell copy with translation resources and localized the first user-visible surfaces: public landing, login entry screens, admin dashboard, candidate dashboard, and the candidate classroom shell. Course categories are now translation-driven, which downstream slices can reuse when course data becomes bilingual.

Verification was strong on the server-rendered side: feature suites for locale switching, authentication, and admin access all passed, and a production Vite build succeeded. Live browser verification was attempted through a Windows-hosted PHP runtime because the WSL environment lacks native PHP, but browser/curl reachability to that server was blocked by environment networking. That limitation is recorded, not hidden.

## Deviations

Live browser verification was blocked by the available runtime topology. All reachable automated verification passed.

## Files Created/Modified

- `app/Http/Middleware/SetLocale.php` — locale resolution for web requests
- `bootstrap/app.php` / `routes/web.php` / `config/app.php` — locale registration, switch route, and default locale config
- `lang/fr/*`, `lang/ar/*` — bilingual shell/auth/public/dashboard/classroom translations
- `resources/views/components/locale-switcher.blade.php` — shared language switch UI
- `resources/views/layouts/*.blade.php` / `resources/views/auth/login.blade.php` — translated shell/auth surfaces
- `resources/views/marketing/massar.blade.php` — localized public landing shell
- `resources/views/candidate/dashboard.blade.php` / `resources/views/admin/dashboard.blade.php` — localized dashboards
- `resources/views/candidate/courses/*.blade.php` / `app/Models/Course.php` — locale-aware classroom shell and category labels
- `tests/Feature/LocaleSwitchTest.php`, `tests/Feature/Auth/AuthenticationTest.php`, `tests/Feature/AdminAccessTest.php` — verification coverage for S01
