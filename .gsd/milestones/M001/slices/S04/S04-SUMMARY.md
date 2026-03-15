---
id: S04
parent: M001
milestone: M001
provides:
  - Public landing page without the anti-piracy marketing block
  - Bilingual regression proof that the removed section stays absent
requires:
  - slice: S01
    provides: Localized public landing shell and locale switching
affects: [S05]
key_files:
  - resources/views/marketing/massar.blade.php
  - tests/Feature/LocaleSwitchTest.php
key_decisions:
  - "This slice removes the marked section only; broader landing-page redesign is left out of scope."
patterns_established:
  - "When a section is explicitly removed, tests should assert its absence instead of only checking what remains."
drill_down_paths:
  - .gsd/milestones/M001/slices/S04/tasks/T01-SUMMARY.md
verification_result: pass
completed_at: 2026-03-15T09:01:00Z
---

# S04: Public landing cleanup

**The public landing page no longer contains the anti-piracy marketing block the user marked for removal.**

## What Happened

S04 made a focused cleanup to the public marketing page: the anti-piracy section was deleted and the rest of the page was left intact. Because the page is already localized, the removal had to be verified in both French and Arabic, not just one language.

Regression coverage now asserts that the removed section heading does not appear in either locale, and the frontend build remained healthy after the cleanup.

## Deviations

None.

## Files Created/Modified

- `resources/views/marketing/massar.blade.php` — removed anti-piracy block
- `tests/Feature/LocaleSwitchTest.php` — bilingual absence verification
