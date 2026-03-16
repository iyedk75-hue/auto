---
id: T01
parent: S04
milestone: M001
provides:
  - Public landing page without the anti-piracy marketing block
  - Landing-page regression checks proving the removed section stays absent in French and Arabic
  - Production-build proof after the landing-page cleanup
requires:
  - slice: S01
    provides: Localized public landing shell and locale-switching coverage
affects: [S05]
key_files:
  - resources/views/marketing/massar.blade.php
  - tests/Feature/LocaleSwitchTest.php
key_decisions:
  - "The public landing cleanup is a surgical removal, not a redesign; only the user-marked section was deleted."
patterns_established:
  - "Landing-page regression coverage should assert removed content stays absent in both supported languages."
drill_down_paths:
  - .gsd/milestones/M001/slices/S04/tasks/T01-PLAN.md
duration: 15m
verification_result: pass
completed_at: 2026-03-15T09:00:00Z
---

# T01: Remove the public anti-piracy section and recheck bilingual landing rendering

**The red-circled anti-piracy block is now gone from the public landing page, and the home page still renders correctly in French and Arabic.**

## What Happened

Removed the anti-piracy marketing section from `resources/views/marketing/massar.blade.php` without altering the rest of the landing-page structure. The hero and product-feature sections remain intact, so the page flow still reads cleanly after the deletion.

`LocaleSwitchTest` was tightened so the removed section heading is now asserted absent in both French and Arabic. A production Vite build also passed after the cleanup.

## Deviations

None.

## Files Created/Modified

- `resources/views/marketing/massar.blade.php` — public landing page with the anti-piracy section removed
- `tests/Feature/LocaleSwitchTest.php` — absence verification for the removed section in both languages
