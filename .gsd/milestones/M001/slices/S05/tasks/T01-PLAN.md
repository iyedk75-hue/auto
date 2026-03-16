---
estimated_steps: 6
estimated_files: 4
---

# T01: Add final integration coverage and close milestone verification

**Slice:** S05 — End-to-end integration and polish
**Milestone:** M001

## Description

Exercise the assembled milestone through one integration-focused feature suite, rerun the full relevant verification set, and mark the milestone complete if everything holds.

## Steps

1. Write a milestone integration test covering the public language switch, admin bilingual course creation, candidate Arabic lesson viewing, protected asset access, and landing cleanup.
2. Add an integration scenario for the explicit missing-Arabic lesson state.
3. Run the integration suite and fix any gaps it finds.
4. Rerun the full relevant feature suites and production build.
5. Update the requirement contract to reflect validated requirements.
6. Mark the slice and milestone complete in the GSD artifacts.

## Must-Haves

- [ ] The integrated milestone scenarios pass in one assembled verification suite.
- [ ] GSD artifacts reflect milestone completion truthfully based on passing verification.

## Verification

- `php artisan test --filter=MilestoneIntegrationTest`
- Full milestone verification set listed in `S05-PLAN.md`

## Inputs

- `.gsd/milestones/M001/M001-SUMMARY.md` — compressed work from S01-S04
- `.gsd/milestones/M001/slices/S04/S04-SUMMARY.md` — latest completed slice context
- existing feature suites for locale, course localization, admin course CRUD, and protected assets

## Expected Output

- `tests/Feature/MilestoneIntegrationTest.php` — assembled milestone proof
- updated `.gsd/REQUIREMENTS.md`, `.gsd/STATE.md`, and milestone artifacts reflecting validated completion
