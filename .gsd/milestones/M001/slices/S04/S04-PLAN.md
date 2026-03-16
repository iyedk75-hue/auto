# S04: Public landing cleanup

**Goal:** Remove the public anti-piracy marketing block the user marked in red without breaking the bilingual landing-page flow.
**Demo:** The public landing page no longer shows the anti-piracy section in French or Arabic, and the rest of the page still renders cleanly.

## Must-Haves

- The red-circled anti-piracy marketing block is removed from the public landing page.
- French and Arabic home pages still render correctly after the section removal.
- Landing-page spacing and CTA flow remain coherent after the block disappears.

## Proof Level

- This slice proves: integration
- Real runtime required: no
- Human/UAT required: no

## Verification

- `php artisan test --filter=LocaleSwitchTest`
- `npm run build`

## Tasks

- [x] **T01: Remove the public anti-piracy section and recheck bilingual landing rendering** `est:20m`
  - Why: The user explicitly wants the red-circled block gone from the public marketing page.
  - Files: `resources/views/marketing/massar.blade.php`, `tests/Feature/LocaleSwitchTest.php`
  - Do: Delete the public anti-piracy section from the landing page, keep the surrounding layout balanced, and extend verification so the removed section does not reappear in French or Arabic.
  - Verify: `php artisan test --filter=LocaleSwitchTest && npm run build`
  - Done when: The section is absent in both languages and the landing page verification still passes.

## Files Likely Touched

- `resources/views/marketing/massar.blade.php`
- `tests/Feature/LocaleSwitchTest.php`
