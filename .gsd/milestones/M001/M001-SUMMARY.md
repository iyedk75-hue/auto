---
id: M001
completed_slices:
  - S01
  - S02
  - S03
active_slices:
  - S04
  - S05
completed_at: 2026-03-15T08:47:00Z
---

# M001: Bilingual protected learning experience — Summary

## Completed Slices

### S01 — Language foundation and RTL shell
- Added middleware-backed French/Arabic locale switching with session/cookie persistence.
- Added translated shared navigation, guest/auth shell, and reusable language switcher component.
- Localized the public landing shell, candidate dashboard, admin dashboard, and classroom shell.
- Added Arabic-aware feature coverage for locale switching, login screens, and admin dashboard copy.
- Verified with passing Laravel feature suites and a production Vite build; live browser reachability was blocked by environment networking to the Windows-hosted PHP runtime.

### S02 — Bilingual course content management
- Extended `courses` with Arabic title, description, and lesson-body fields.
- Added locale-aware course text helpers on the Course model.
- Upgraded admin course CRUD to author French and Arabic lesson text side by side.
- Updated candidate lesson rendering to use Arabic content when available and show a clear unavailable state when it is not.
- Verified with passing `CourseLocalizationTest`, `AdminCourseTest`, `LocaleSwitchTest`, and a production Vite build.

### S03 — Protected inline learning viewer
- Moved new lesson media/PDF uploads to private local storage while preserving legacy public-asset compatibility.
- Added authenticated inline media/PDF routes for candidate viewing and admin preview.
- Removed direct public lesson-asset URLs from candidate lesson HTML.
- Added visible viewer deterrence and page-scoped blocking for right-click and common save/copy shortcuts.
- Verified with passing `CourseProtectionTest`, `AdminCourseTest`, and a production Vite build.

## What This Unlocks Next

- S04 can remove the public anti-piracy block from an already localized landing page without affecting lesson protection.
- S05 can verify the assembled bilingual admin/student flow, private lesson delivery, and public landing cleanup together end-to-end.

## Drill-Down Paths

- `.gsd/milestones/M001/slices/S01/S01-SUMMARY.md`
- `.gsd/milestones/M001/slices/S02/S02-SUMMARY.md`
- `.gsd/milestones/M001/slices/S03/S03-SUMMARY.md`
- `.gsd/milestones/M001/slices/S01/tasks/T01-SUMMARY.md`
- `.gsd/milestones/M001/slices/S01/tasks/T02-SUMMARY.md`
- `.gsd/milestones/M001/slices/S01/tasks/T03-SUMMARY.md`
- `.gsd/milestones/M001/slices/S02/tasks/T01-SUMMARY.md`
- `.gsd/milestones/M001/slices/S02/tasks/T02-SUMMARY.md`
- `.gsd/milestones/M001/slices/S02/tasks/T03-SUMMARY.md`
- `.gsd/milestones/M001/slices/S03/tasks/T01-SUMMARY.md`
- `.gsd/milestones/M001/slices/S03/tasks/T02-SUMMARY.md`
- `.gsd/milestones/M001/slices/S03/tasks/T03-SUMMARY.md`
