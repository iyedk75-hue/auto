# S05: End-to-end integration and polish

**Goal:** Recheck the milestone as one assembled system and close any remaining integration gaps surfaced by that final proof pass.
**Demo:** The bilingual public/admin/student flow, protected lesson delivery, missing-Arabic behavior, and landing cleanup all prove out together under one final verification pass.

## Must-Haves

- A milestone-level integration test exercises the assembled guest/admin/candidate flow.
- The final verification pass rechecks bilingual home switching, bilingual admin course authoring, protected lesson asset access, Arabic lesson rendering, missing-Arabic handling, and landing cleanup together.
- Any small integration gaps found during the final pass are fixed before the milestone is closed.

## Proof Level

- This slice proves: final-assembly
- Real runtime required: yes
- Human/UAT required: no

## Verification

- `php artisan test --filter=MilestoneIntegrationTest`
- `php artisan test --filter=AuthenticationTest`
- `php artisan test --filter=LocaleSwitchTest`
- `php artisan test --filter=AdminAccessTest`
- `php artisan test --filter=AdminCourseTest`
- `php artisan test --filter=CourseLocalizationTest`
- `php artisan test --filter=CourseProtectionTest`
- `npm run build`

## Tasks

- [x] **T01: Add final integration coverage and close milestone verification** `est:1h`
  - Why: The milestone promise spans public marketing, admin authoring, candidate viewing, locale switching, and protected assets, so slice-local tests are not enough on their own.
  - Files: `tests/Feature/MilestoneIntegrationTest.php`, `.gsd/REQUIREMENTS.md`, `.gsd/STATE.md`, `.gsd/milestones/M001/M001-SUMMARY.md`
  - Do: Write milestone-level integration tests covering the assembled flow, run the full relevant suite and build, fix any gaps that surface, then update requirement and milestone state to reflect validated completion.
  - Verify: `php artisan test --filter=MilestoneIntegrationTest` plus the full milestone verification set and production build
  - Done when: The integrated milestone scenarios pass and the GSD artifacts show M001 as complete.

## Files Likely Touched

- `tests/Feature/MilestoneIntegrationTest.php`
- `.gsd/REQUIREMENTS.md`
- `.gsd/STATE.md`
- `.gsd/milestones/M001/M001-SUMMARY.md`
