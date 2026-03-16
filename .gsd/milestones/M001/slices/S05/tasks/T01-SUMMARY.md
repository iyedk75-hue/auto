---
id: T01
parent: S05
milestone: M001
provides:
  - Milestone-level integration test covering the assembled public/admin/candidate flow
  - Final passing milestone verification set across auth, locale, admin, course, protection, and build surfaces
  - Updated requirement contract reflecting validated milestone completion
requires:
  - slice: S01
    provides: locale switching and translated shell
  - slice: S02
    provides: bilingual course content and missing-Arabic state
  - slice: S03
    provides: protected lesson delivery and deterrence
  - slice: S04
    provides: cleaned landing page
affects: []
key_files:
  - tests/Feature/MilestoneIntegrationTest.php
  - .gsd/REQUIREMENTS.md
  - .gsd/STATE.md
  - .gsd/milestones/M001/M001-SUMMARY.md
key_decisions:
  - "Milestone completion is based on the strongest reachable proof in this environment: integration tests plus the full relevant feature suite and production build."
patterns_established:
  - "Final milestone closure should add at least one assembled integration suite, not just rely on lower-level tests."
drill_down_paths:
  - .gsd/milestones/M001/slices/S05/tasks/T01-PLAN.md
duration: 35m
verification_result: pass
completed_at: 2026-03-15T09:20:00Z
---

# T01: Add final integration coverage and close milestone verification

**The milestone now has one assembled proof surface, and the full relevant verification set passed cleanly.**

## What Happened

Added `MilestoneIntegrationTest` to exercise the assembled product path: public Arabic home rendering without the removed landing section, admin creation of a bilingual protected course, candidate Arabic lesson viewing through protected asset routes, guest denial of those asset routes, and the explicit missing-Arabic lesson state.

After the integration suite passed, the full relevant verification set was rerun: authentication, locale switching, admin access, admin course CRUD, course localization, protected asset delivery, milestone integration, and a production Vite build. All passed.

The requirement contract was then updated so the milestone’s active requirements now read as validated instead of merely mapped.

## Deviations

Live browser proof is still constrained by environment networking to the Windows-hosted PHP runtime, so final closure relies on the strongest reachable automated proof surfaces.

## Files Created/Modified

- `tests/Feature/MilestoneIntegrationTest.php` — assembled milestone verification
- `.gsd/REQUIREMENTS.md` — validated requirement status updates
- `.gsd/STATE.md` — milestone completion state
- `.gsd/milestones/M001/M001-SUMMARY.md` — final milestone rollup
