---
estimated_steps: 4
estimated_files: 2
---

# T01: Remove the public anti-piracy section and recheck bilingual landing rendering

**Slice:** S04 — Public landing cleanup
**Milestone:** M001

## Description

Delete the anti-piracy marketing block from the public landing page and verify it stays gone in both French and Arabic.

## Steps

1. Remove the anti-piracy section from `marketing/massar.blade.php`.
2. Keep the surrounding landing-page spacing and CTA flow clean after removal.
3. Extend landing-page verification so the removed section text does not appear in French or Arabic.
4. Run the landing-page verification command and production build.

## Must-Haves

- [ ] The anti-piracy landing section is gone in both languages.
- [ ] The landing page still renders its remaining hero and feature sections cleanly.

## Verification

- `php artisan test --filter=LocaleSwitchTest`
- `npm run build`

## Inputs

- `.gsd/milestones/M001/M001-ROADMAP.md` — S04 demo contract
- `resources/views/marketing/massar.blade.php` — current landing page with the removable section

## Expected Output

- `resources/views/marketing/massar.blade.php` — cleaned landing page without the anti-piracy block
- `tests/Feature/LocaleSwitchTest.php` — landing-page absence verification
