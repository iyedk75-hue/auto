---
id: T02
parent: S01
milestone: M001
provides:
  - French and Arabic translation resources for shared shell and auth surfaces
  - Reusable locale switcher component for guest and authenticated navigation
  - Localized guest/auth layout copy with Arabic-capable typography
  - Translated shared navigation labels across public, candidate, and admin shells
  - Arabic login-screen feature coverage in AuthenticationTest
requires:
  - slice: S01
    provides: Locale middleware, switch route, and shell `lang` / `dir` behavior from T01
affects: [S03, S04, S05]
key_files:
  - lang/fr/ui.php
  - lang/ar/ui.php
  - lang/fr.json
  - lang/ar.json
  - lang/fr/auth.php
  - lang/ar/auth.php
  - resources/views/components/locale-switcher.blade.php
  - resources/views/layouts/navigation.blade.php
  - resources/views/layouts/guest.blade.php
  - resources/views/auth/login.blade.php
  - resources/css/app.css
  - app/Http/Controllers/Auth/AuthenticatedSessionController.php
  - tests/Feature/Auth/AuthenticationTest.php
key_decisions:
  - "Use PHP array files for app-specific UI labels and JSON files for Breeze-style string translations."
  - "Expose the language switcher as a reusable Blade component so public and authenticated shells stay consistent."
patterns_established:
  - "Shared shell text resolves through `ui.*` translation keys; built-in Breeze labels use JSON translation files."
  - "Locale switch UI lives as a reusable component, not copied markup."
drill_down_paths:
  - .gsd/milestones/M001/slices/S01/tasks/T02-PLAN.md
duration: 1h
verification_result: pass
completed_at: 2026-03-15T06:55:00Z
---

# T02: Translate shared layouts, navigation, and auth entry screens

**The guest/auth shell is now bilingual, with one shared language-switcher pattern and Arabic login views proven by feature tests.**

## What Happened

Added `lang/fr` and `lang/ar` translation resources for app-specific shell labels, plus JSON translation files so the existing Breeze-style `__()` strings on auth/profile pages have real French and Arabic output instead of falling back to English. A new `locale-switcher` Blade component now drives language changes from both guest and authenticated navigation surfaces.

The guest layout and the main navigation partial were rewritten to remove hard-coded mixed-language labels and replace them with translated copy. The login entry screen now uses the same translation resources, and the role-specific login validation messages were routed through translation keys instead of hard-coded French strings.

CSS gained a small locale-switcher style surface, Arabic font support for RTL mode, and initial RTL shell adjustments for the sidebar, brand rows, and guest/auth panels. Authentication feature tests were extended so Arabic login pages are now part of the real verification surface rather than a manual-only check.

## Deviations

None.

## Files Created/Modified

- `lang/fr/ui.php` / `lang/ar/ui.php` — app-specific shell/auth labels
- `lang/fr.json` / `lang/ar.json` — translations for existing Breeze-style `__()` strings
- `lang/fr/auth.php` / `lang/ar/auth.php` — auth failure/throttle/password messages
- `resources/views/components/locale-switcher.blade.php` — reusable language switch component
- `resources/views/layouts/navigation.blade.php` — translated public/authenticated navigation
- `resources/views/layouts/guest.blade.php` — translated guest/auth shell with switcher
- `resources/views/auth/login.blade.php` — translated candidate/admin login entry view
- `resources/css/app.css` — locale switcher styles and initial RTL shell rules
- `app/Http/Controllers/Auth/AuthenticatedSessionController.php` — translated role-specific login errors
- `tests/Feature/Auth/AuthenticationTest.php` — Arabic login-screen verification
