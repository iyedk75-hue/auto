---
estimated_steps: 6
estimated_files: 7
---

# T03: Localize core dashboards and classroom shell

**Slice:** S01 — Language foundation and RTL shell
**Milestone:** M001

## Description

Localize the first user-visible public/admin/candidate surfaces and add the RTL-safe styling adjustments needed so the app is genuinely usable in Arabic mode.

## Steps

1. Add translation keys for public landing, admin dashboard, candidate dashboard, and course-shell labels.
2. Replace hard-coded labels in the public landing page and the admin/candidate dashboard entry views.
3. Translate the course list/detail shell labels that downstream slices will build on.
4. Add CSS adjustments for RTL sidebar/layout behavior where the existing left-to-right shell breaks.
5. Run the focused dashboard tests and start a browser session for live UI verification.
6. Verify French/Arabic rendering on public, admin, and candidate routes and capture any gaps before closing the slice.

## Must-Haves

- [ ] Public, admin, and candidate entry surfaces render localized shell text in both languages.
- [ ] Arabic mode uses RTL without breaking the main sidebar/navigation/dashboard composition.

## Verification

- `php artisan test --filter=AdminAccessTest`
- Browser verification of `/`, `/login`, `/dashboard`, and `/admin/dashboard` in French and Arabic.

## Observability Impact

- Signals added/changed: visible active-language state in navigation and RTL page direction on live pages.
- How a future agent inspects this: browser verification plus rendered HTML/CSS on the affected routes.
- Failure state exposed: broken RTL composition or untranslated page chrome is directly visible on the first user-facing routes.

## Inputs

- `.gsd/milestones/M001/slices/S01/tasks/T02-PLAN.md` — translated shell and language switcher contract
- `resources/views/marketing/massar.blade.php` — public surface to localize
- `resources/views/candidate/dashboard.blade.php` — candidate entry dashboard
- `resources/views/admin/dashboard.blade.php` — admin entry dashboard

## Expected Output

- `resources/views/marketing/massar.blade.php` — translated public landing shell
- `resources/views/candidate/dashboard.blade.php` and `resources/views/admin/dashboard.blade.php` — localized dashboards
- `resources/views/candidate/courses/*.blade.php` and `resources/css/app.css` — locale-aware classroom shell and RTL styling support
