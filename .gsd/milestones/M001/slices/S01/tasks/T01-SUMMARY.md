---
id: T01
parent: S01
milestone: M001
provides:
  - Middleware-backed locale selection for all web requests
  - Shared `/locale/{locale}` switch route with session + cookie persistence
  - Default application locale changed to French with supported locale list
  - Layout shell now emits `lang` and `dir` attributes from active locale
  - Feature tests proving guest, candidate, and admin locale behavior
requires: []
affects: [S02, S03, S04, S05]
key_files:
  - app/Http/Middleware/SetLocale.php
  - bootstrap/app.php
  - routes/web.php
  - config/app.php
  - resources/views/layouts/app.blade.php
  - resources/views/layouts/guest.blade.php
  - tests/Feature/LocaleSwitchTest.php
key_decisions:
  - "Default locale is French because the current product copy is already French-first."
  - "Locale state is persisted through both session and cookie so guest and authenticated flows behave the same."
patterns_established:
  - "Web middleware owns locale resolution; views read locale state instead of controllers setting it ad hoc."
  - "Language switching is a shared route-level behavior, not page-local UI logic."
drill_down_paths:
  - .gsd/milestones/M001/slices/S01/tasks/T01-PLAN.md
duration: 1h
verification_result: pass
completed_at: 2026-03-15T06:35:00Z
---

# T01: Wire locale switching and prove it with feature tests

**Locale state is now a first-class web concern, with tests proving French/Arabic switching across guest and authenticated surfaces.**

## What Happened

Added `SetLocale` as a web middleware, registered it in the Laravel 12 bootstrap pipeline, and introduced a shared `locale.switch` route that persists `fr` / `ar` in both session and cookie state. The application default locale was changed to French and supported locales were made explicit in config so later slices can reuse one canonical list.

The app and guest layouts now expose locale state through `lang` and `dir`, which gave the test suite a stable way to assert language direction before any page-copy translation work starts. While proving the flow, the new tests caught a Blade variable-scope issue in `layouts/app.blade.php`; that was fixed immediately instead of deferring it into the translation task.

Verification ran in a Windows-filesystem copy of the repo because this WSL environment has no native PHP runtime. Composer dependencies and Vite assets were installed in that verification copy, then the focused locale feature tests passed there.

## Deviations

Used a Windows-side verification copy under `/mnt/c/temp/auto-gsd-test` for PHP/Laravel test execution because the working WSL environment does not have a usable native PHP runtime or Docker integration.

## Files Created/Modified

- `app/Http/Middleware/SetLocale.php` — resolves active locale from session/cookie for all web requests
- `bootstrap/app.php` — appends locale middleware to the web stack
- `routes/web.php` — adds the language switch route and persistence behavior
- `config/app.php` — sets French defaults and supported locale list
- `resources/views/layouts/app.blade.php` — emits locale-aware `lang`/`dir` and body classes
- `resources/views/layouts/guest.blade.php` — emits locale-aware `lang`/`dir` and body classes
- `tests/Feature/LocaleSwitchTest.php` — proves guest, candidate, admin, and invalid-locale behavior
