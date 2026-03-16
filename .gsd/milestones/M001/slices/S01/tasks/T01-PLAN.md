---
estimated_steps: 6
estimated_files: 4
---

# T01: Wire locale switching and prove it with feature tests

**Slice:** S01 — Language foundation and RTL shell
**Milestone:** M001

## Description

Add the shared locale plumbing so the app can switch between French and Arabic across web requests, then prove it with focused feature tests before view translation work starts.

## Steps

1. Create a web middleware that resolves the active locale from session/cookie state and applies `fr` or `ar` only.
2. Register the middleware in Laravel's web stack so it runs for public, candidate, and admin pages.
3. Add a shared language-switch route/action that validates the requested locale, stores it, and redirects back safely.
4. Ensure layouts can rely on locale state for `lang` and `dir` output without controller-specific duplication.
5. Write feature tests covering guest switching, persistence across navigation, and authenticated candidate/admin rendering.
6. Run the focused locale test suite and fix any failures.

## Must-Haves

- [ ] Locale is selected from one shared web mechanism rather than duplicated per controller.
- [ ] Feature tests prove French/Arabic switching, persistence, and `lang` / `dir` shell behavior.

## Verification

- `php artisan test --filter=LocaleSwitchTest`
- Confirm the locale switch route redirects back and preserves the requested language across subsequent requests.

## Observability Impact

- Signals added/changed: rendered `lang` and `dir` attributes now expose the active locale directly in markup.
- How a future agent inspects this: run `php artisan test --filter=LocaleSwitchTest` and inspect the rendered page source for `lang` / `dir`.
- Failure state exposed: unsupported or missing locale selection leaves tests failing with wrong markup or untranslated shell state.

## Inputs

- `bootstrap/app.php` — current middleware registration point
- `routes/web.php` — public route surface where the language switch endpoint can live
- `.gsd/milestones/M001/M001-ROADMAP.md` — S01 boundary-map contract for locale middleware and shared switch state

## Expected Output

- `app/Http/Middleware/SetLocale.php` — shared locale resolver for web requests
- `routes/web.php` — language switch endpoint
- `tests/Feature/LocaleSwitchTest.php` — focused proof for locale switching and shell attributes
