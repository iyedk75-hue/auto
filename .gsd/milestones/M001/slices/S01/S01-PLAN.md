# S01: Language foundation and RTL shell

**Goal:** Establish the locale plumbing, shared shell behavior, and translated entry surfaces needed for French/Arabic navigation across the public site, candidate area, and admin area.
**Demo:** A guest, candidate, or admin can switch between French and Arabic, keep that choice while navigating, and see the main shared UI render in RTL when Arabic is active.

## Must-Haves

- Locale switching works through a shared mechanism for guest and authenticated users and persists during navigation.
- Shared layouts set the correct `lang` and `dir` attributes and render Arabic in RTL without breaking the shell.
- Public navigation, auth entry screens, candidate dashboard chrome, admin dashboard chrome, and course-shell labels are translated into French and Arabic.
- Slice outputs match the boundary map contracts for S01 → S02, S01 → S03, and S01 → S04.

## Proof Level

- This slice proves: integration
- Real runtime required: yes
- Human/UAT required: no

## Verification

- `php artisan test --filter=LocaleSwitchTest`
- `php artisan test --filter=AuthenticationTest`
- `php artisan test --filter=AdminAccessTest`
- Browser verification: guest home, login, candidate dashboard, and admin dashboard render in both languages with RTL in Arabic mode

## Observability / Diagnostics

- Runtime signals: current locale is visible through the rendered `lang` / `dir` attributes and active language-switch UI state
- Inspection surfaces: feature tests, shared navigation UI, rendered page source, session-backed language behavior in browser flows
- Failure visibility: wrong locale leaves untranslated strings or incorrect `dir`, which is directly visible in tests and page markup
- Redaction constraints: none

## Integration Closure

- Upstream surfaces consumed: existing Blade layouts, `routes/web.php`, `routes/auth.php`, `bootstrap/app.php`, auth/dashboard controllers and views
- New wiring introduced in this slice: locale middleware in the web stack, language-switch route, translation files, RTL-aware shared shell classes
- What remains before the milestone is truly usable end-to-end: bilingual course data, protected private asset delivery, landing cleanup, and final assembly verification

## Tasks

- [x] **T01: Wire locale switching and prove it with feature tests** `est:45m`
  - Why: Later slices depend on one stable source of truth for language selection instead of page-local conditionals.
  - Files: `bootstrap/app.php`, `app/Http/Middleware/SetLocale.php`, `routes/web.php`, `tests/Feature/LocaleSwitchTest.php`
  - Do: Add middleware-backed locale selection for the web stack, add a shared language-switch endpoint, constrain allowed locales to French/Arabic, and write feature tests that prove persistence and `lang`/`dir` behavior across guest and authenticated routes.
  - Verify: `php artisan test --filter=LocaleSwitchTest`
  - Done when: Locale changes survive redirects/navigation and tests prove French/Arabic shell state on public, candidate, and admin pages.
- [x] **T02: Translate shared layouts, navigation, and auth entry screens** `est:1h`
  - Why: Shared shell text must be localized before page-level work can land cleanly in later slices.
  - Files: `resources/views/layouts/app.blade.php`, `resources/views/layouts/guest.blade.php`, `resources/views/layouts/navigation.blade.php`, `resources/views/auth/login.blade.php`, `lang/fr/*.php`, `lang/ar/*.php`
  - Do: Introduce translation resources, replace hard-coded shell/auth strings with translation keys, add a reusable language switcher in guest and authenticated navigation, and make the base layouts RTL-aware in Arabic mode.
  - Verify: `php artisan test --filter=AuthenticationTest`
  - Done when: Guest/auth pages and shared navigation render in both languages without mixed hard-coded shell labels.
- [ ] **T03: Localize core dashboards and classroom shell, then verify in browser** `est:1h`
  - Why: S01 must hand downstream slices a real locale-aware public/admin/candidate surface, not just hidden plumbing.
  - Files: `resources/views/marketing/massar.blade.php`, `resources/views/candidate/dashboard.blade.php`, `resources/views/admin/dashboard.blade.php`, `resources/views/candidate/courses/index.blade.php`, `resources/views/candidate/courses/show.blade.php`, `resources/css/app.css`
  - Do: Translate the core dashboard and course-shell labels, add any RTL-safe CSS adjustments needed for the sidebar/nav/layout composition, and verify the public/admin/candidate entry flows in a running browser session.
  - Verify: `php artisan test --filter=AdminAccessTest` plus browser verification of home/login/dashboard routes in French and Arabic
  - Done when: The main surfaces named in the slice demo render correctly in both languages, with Arabic using RTL and no broken shell composition.

## Files Likely Touched

- `bootstrap/app.php`
- `app/Http/Middleware/SetLocale.php`
- `routes/web.php`
- `resources/views/layouts/app.blade.php`
- `resources/views/layouts/guest.blade.php`
- `resources/views/layouts/navigation.blade.php`
- `resources/views/auth/login.blade.php`
- `resources/views/marketing/massar.blade.php`
- `resources/views/candidate/dashboard.blade.php`
- `resources/views/admin/dashboard.blade.php`
- `resources/views/candidate/courses/index.blade.php`
- `resources/views/candidate/courses/show.blade.php`
- `resources/css/app.css`
- `lang/fr/ui.php`
- `lang/ar/ui.php`
- `tests/Feature/LocaleSwitchTest.php`
