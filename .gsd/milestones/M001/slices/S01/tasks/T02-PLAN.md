---
estimated_steps: 6
estimated_files: 8
---

# T02: Translate shared layouts, navigation, and auth entry screens

**Slice:** S01 — Language foundation and RTL shell
**Milestone:** M001

## Description

Replace hard-coded shell/auth strings with translation resources, add a reusable language switcher, and make the base guest/authenticated layouts respect Arabic RTL direction.

## Steps

1. Create the initial French and Arabic translation resources for shared shell/auth labels.
2. Update the guest and authenticated layouts to emit locale-aware metadata and RTL-aware body classes.
3. Add a reusable language switch UI to guest and authenticated navigation.
4. Replace hard-coded shared navigation labels with translation keys.
5. Replace login/auth entry copy with translation keys and remove mixed-language shell text.
6. Run auth-related tests and fix any regressions.

## Must-Haves

- [ ] Shared shell and auth entry screens render from translation resources in both French and Arabic.
- [ ] Navigation exposes a working language switcher for guest and authenticated users.

## Verification

- `php artisan test --filter=AuthenticationTest`
- Manually inspect the login and admin login screens in both languages.

## Inputs

- `.gsd/milestones/M001/slices/S01/tasks/T01-PLAN.md` — locale middleware and switch route contract
- `resources/views/layouts/app.blade.php` — authenticated shell
- `resources/views/layouts/guest.blade.php` — guest/auth shell
- `resources/views/layouts/navigation.blade.php` — shared navigation surface

## Expected Output

- `lang/fr/ui.php` and `lang/ar/ui.php` — shared shell/auth translations
- `resources/views/layouts/*.blade.php` — locale-aware layout and switcher wiring
- `resources/views/auth/login.blade.php` — translated login surface
